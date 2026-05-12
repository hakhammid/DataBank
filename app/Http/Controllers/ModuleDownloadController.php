<?php
namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleDownload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleDownloadController extends Controller
{
    public function downloadModule($id, Request $request)
    {
        $user = Auth::user();

        // Check today's download count
        $downloadsToday = $user->moduleDownloads()
            ->whereDate('downloaded_at', Carbon::today())
            ->count();

        if ($downloadsToday >= 5) {
            return back()->with('error', 'You have reached your daily download limit of 5 modules.');
        }

        // Find the module
        $module = Module::findOrFail($id);

        // Log the download
        ModuleDownload::create([
            'user_id'       => $user->id,
            'module_id'     => $module->id,
            'downloaded_at' => now(),
        ]);

        // Increase module view count
        $module->increment('number_of_views');

        $filePath = public_path('files/' . $module->file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $module->file);

    }
}
