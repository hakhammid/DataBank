<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ModuleDownload;
use Illuminate\Support\Facades\Auth;

class DownloadHistory extends Component
{
    use WithPagination;

    public $remainingQuota;

    public function mount()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $this->updateQuota();
    }

    public function updateQuota()
    {
        $this->remainingQuota = max(0, 5 - ModuleDownload::where('user_id', Auth::id())
            ->whereDate('downloaded_at', Carbon::today())
            ->count());
    }

    public function render()
    {
        $downloads = collect();

        if (Auth::check()) {
            $downloads = ModuleDownload::with(['module.user', 'module.department', 'module.course'])
                ->where('user_id', Auth::id())
                ->orderBy('downloaded_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.download-history', [
            'downloads' => $downloads
        ])->layout('layouts.app'); // Ensure it uses the main layout if we hit it via route
    }
}
