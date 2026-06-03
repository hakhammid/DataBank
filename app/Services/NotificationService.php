<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Notification;
use App\Models\User;
use App\Models\ModuleEnrollment;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notify all eligible students about a newly published module.
     *
     * Eligible students are those who:
     * 1. Have a course_id matching one of the module's degree programs (via module_courses pivot), OR
     * 2. Are explicitly enrolled in the module's course_code (via module_enrollments).
     */
    public static function notifyStudentsOfNewModule(Module $module): int
    {
        try {
            // Get course IDs from the module's degree programs
            $courseIds = $module->courses()->pluck('courses.id')->toArray();

            // Get student IDs enrolled in this course code
            $enrolledStudentIds = ModuleEnrollment::where('course_code', $module->course_code)
                ->pluck('user_id')
                ->toArray();

            // Get student IDs whose degree program matches
            $programStudentIds = [];
            if (!empty($courseIds)) {
                $programStudentIds = User::where('usertype', 'student')
                    ->whereIn('course_id', $courseIds)
                    ->pluck('id')
                    ->toArray();
            }

            // Merge and deduplicate
            $allStudentIds = array_unique(array_merge($programStudentIds, $enrolledStudentIds));

            if (empty($allStudentIds)) {
                return 0;
            }

            $message = "New module uploaded: {$module->course_code} — {$module->title}";

            $notifications = [];
            $now = now();

            foreach ($allStudentIds as $studentId) {
                // Avoid duplicate notifications for the same module
                $exists = Notification::where('user_id', $studentId)
                    ->where('module_id', $module->id)
                    ->exists();

                if (!$exists) {
                    $notifications[] = [
                        'user_id' => $studentId,
                        'module_id' => $module->id,
                        'message' => $message,
                        'read_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($notifications)) {
                // Batch insert for performance
                Notification::insert($notifications);
            }

            return count($notifications);
        } catch (\Exception $e) {
            Log::error('NotificationService error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Notify the faculty member (module owner) that their module has been published by admin.
     */
    public static function notifyFacultyOfPublishedModule(Module $module): bool
    {
        try {
            $faculty = $module->user;

            if (!$faculty || $faculty->usertype !== 'faculty') {
                return false;
            }

            // Avoid duplicate
            $exists = Notification::where('user_id', $faculty->id)
                ->where('module_id', $module->id)
                ->where('message', 'like', '%has been approved%')
                ->exists();

            if ($exists) {
                return false;
            }

            Notification::create([
                'user_id' => $faculty->id,
                'module_id' => $module->id,
                'message' => "Your module \"{$module->title}\" ({$module->course_code}) has been approved and published.",
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('NotificationService faculty notify error: ' . $e->getMessage());
            return false;
        }
    }
}
