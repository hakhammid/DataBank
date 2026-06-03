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

        if ($user->usertype === 'student') {
            if ($module->status !== 'published') {
                abort(403, 'This module is pending approval and cannot be downloaded.');
            }

            // Check if the student is explicitly enrolled in this module's course code
            $isEnrolled = \App\Models\ModuleEnrollment::where('user_id', $user->id)
                ->where('course_code', $module->course_code)
                ->exists();

            if (!$isEnrolled) {
                abort(403, 'You are not enrolled in this module\'s course code and cannot download it.');
            }
        }

        // Log the download
        ModuleDownload::create([
            'user_id'       => $user->id,
            'module_id'     => $module->id,
            'downloaded_at' => now(),
        ]);

        if ($user->usertype === 'student') {
            \App\Services\NotificationService::notifyFacultyOfDownload($module, $user);
        }

        // Increase module view count
        $module->increment('number_of_views');

        $filePath = public_path('files/' . $module->file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $module->file);

    }
}
