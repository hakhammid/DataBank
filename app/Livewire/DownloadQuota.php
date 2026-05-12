<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\ModuleDownload;
use Illuminate\Support\Facades\Auth;

class DownloadQuota extends Component
{
    public $remainingQuota;

    public function mount()
    {
        $this->updateQuota();
    }

    public function updateQuota($data = null)
    {
        if ($data && isset($data['remainingQuota'])) {
            $this->remainingQuota = $data['remainingQuota'];
        } else {
            $this->remainingQuota = max(0, 5 - ModuleDownload::where('user_id', Auth::id())
                ->whereDate('downloaded_at', Carbon::today())
                ->count());
        }

        $this->dispatch('quotaUpdated', remainingQuota: $this->remainingQuota);
    }
    public function render()
    {
        return view('livewire.download-quota', [
            'remainingQuota' => $this->remainingQuota
        ]);
    }
}
