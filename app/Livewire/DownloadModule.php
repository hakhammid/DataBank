<?php
namespace App\Livewire;

use App\Models\Module;
use App\Models\ModuleDownload;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DownloadModule extends Component
{
    public $module;
    public $remainingQuota;
    public $downloadsToday;
    public $maxQuota = 5;

    protected $listeners = ['quotaUpdated' => 'handleQuotaUpdate'];

    public function mount($module)
    {
        $this->module = $module;
        $this->updateQuota();
    }

    public function handleQuotaUpdate($data)
    {
        if (isset($data['remainingQuota'])) {
            $this->remainingQuota = $data['remainingQuota'];
        }
    }

    public function updateQuota()
    {
        $this->downloadsToday = ModuleDownload::where('user_id', Auth::id())
            ->whereDate('downloaded_at', Carbon::today())
            ->count();

        $this->remainingQuota = max(0, $this->maxQuota - $this->downloadsToday);

        // Dispatch the quota update event
        $this->dispatch('quotaUpdated', [
            'remainingQuota' => $this->remainingQuota
        ]);
    }

    public function download()
    {
        // First check quota before proceeding
        $this->updateQuota();

        if ($this->remainingQuota <= 0) {
            $this->dispatch('toast',
                type: 'error',
                message: 'You have reached your daily download limit of 5 modules.'
            );
            return;
        }

        $filePath = public_path('files/' . $this->module->file);

        if (!file_exists($filePath)) {
            $this->dispatch('toast',
                type: 'error',
                message: 'File not found.'
            );
            return;
        }

        // Create the download record
        ModuleDownload::create([
            'user_id' => Auth::id(),
            'module_id' => $this->module->id,
            'downloaded_at' => now(),
        ]);

        // Increment view count
        $this->module->increment('number_of_views');

        // Update quota and notify other components
        $this->updateQuota();

        // Calculate remaining quota after this download
        $newRemainingQuota = $this->remainingQuota;

        // Generate a unique download token
        $downloadToken = uniqid('download_');

        // Store download info in session
        session()->put('pending_download_' . $downloadToken, [
            'file_path' => $filePath,
            'file_name' => $this->module->file,
            'module_id' => $this->module->id
        ]);

        // Show success message with remaining quota
        $quotaMessage = $newRemainingQuota > 0
            ? "Download started! You have {$newRemainingQuota} download" . ($newRemainingQuota !== 1 ? 's' : '') . " remaining today."
            : "Download started! This was your last download for today.";

        $this->dispatch('toast',
            type: 'success',
            message: $quotaMessage
        );

        // Small delay to allow toast to show before redirect
        $this->dispatch('initiate-download', ['token' => $downloadToken]);
    }

    public function render()
    {
        return view('livewire.download-module', [
            'remainingQuota' => $this->remainingQuota
        ]);
    }
}
