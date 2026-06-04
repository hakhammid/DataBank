<?php

namespace App\Livewire;

use Livewire\Component;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Cache;
use Gemini\Laravel\Facades\Gemini;

class AiAssistant extends Component
{
    public $module;
    public $messages = [];
    public $input = '';
    public $isOpen = false;

    public function mount($module)
    {
        $this->module = $module;
        // Initial greeting
        $this->messages[] = [
            'role' => 'assistant',
            'content' => 'Hello! I am your AI tutor. I have read the module "' . $module->title . '". How can I help you today?'
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        $this->validate([
            'input' => 'required|string|max:1000',
        ]);

        $userMessage = $this->input;
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];
        $this->input = '';

        try {
            $response = $this->getGeminiResponse($userMessage);
            
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response
            ];
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Sorry, I encountered an error connecting to the AI: ' . $e->getMessage() . '. Please ensure GEMINI_API_KEY is configured in your .env file.'
            ];
        }
    }

    private function getGeminiResponse($userMessage)
    {
        // Extract PDF text (cached)
        $pdfText = $this->getPdfText();

        // Build prompt
        $prompt = "You are a helpful and encouraging AI tutor assisting a student with their learning module.\n";
        $prompt .= "Module Title: " . $this->module->title . "\n";
        
        if ($pdfText) {
            // Limit text to avoid hitting token limits
            $context = substr($pdfText, 0, 25000); 
            $prompt .= "Module Context:\n" . $context . "\n\n";
        }
        
        $prompt .= "Chat History:\n";
        foreach ($this->messages as $msg) {
            if ($msg['role'] === 'user') {
                $prompt .= "Student: " . $msg['content'] . "\n";
            } else {
                $prompt .= "Tutor: " . $msg['content'] . "\n";
            }
        }
        
        $prompt .= "Student: " . $userMessage . "\n";
        $prompt .= "Tutor: ";
        
        $result = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
        
        return $result->text();
    }

    private function getPdfText()
    {
        // Change cache key to invalidate any old malformed cached data
        $cacheKey = 'module_pdf_text_v2_' . $this->module->id;
        
        return Cache::remember($cacheKey, now()->addDays(7), function () {
            try {
                $filePath = public_path('files/' . $this->module->file);
                if (!file_exists($filePath)) {
                    return null;
                }
                
                $parser = new Parser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
                
                // Sanitize text: remove invalid UTF-8 characters
                $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
                // Remove non-printable control characters (except newlines/tabs)
                $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
                
                // Clean up excessive whitespace
                return preg_replace('/\s+/', ' ', $text);
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function render()
    {
        return view('livewire.ai-assistant');
    }
}
