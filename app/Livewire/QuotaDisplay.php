<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\ModuleDownload;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class QuotaDisplay extends Component
{
    public $remainingQuota;
    public $maxQuota = 5;

    protected $listeners = ['quotaUpdated' => 'handleQuotaUpdate'];

    public function mount()
    {
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
        $downloadsToday = ModuleDownload::where('user_id', Auth::id())
            ->whereDate('downloaded_at', Carbon::today())
            ->count();
            
        $this->remainingQuota = max(0, $this->maxQuota - $downloadsToday);
    }

    public function render()
    {
        return view('livewire.quota-display');
    }
} 