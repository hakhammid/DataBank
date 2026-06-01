<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Copy existing module -> course relationships to the pivot table
        $modules = DB::table('modules')->whereNotNull('course_id')->get();

        foreach ($modules as $module) {
            DB::table('module_courses')->insertOrIgnore([
                'module_id'  => $module->id,
                'course_id'  => $module->course_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 2: Drop the old course_id foreign key and column from modules
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the course_id column
        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('department_id')->constrained('courses')->nullOnDelete();
        });

        // Restore data from pivot back to modules (take the first course_id per module)
        $pivotRecords = DB::table('module_courses')
            ->select('module_id', DB::raw('MIN(course_id) as course_id'))
            ->groupBy('module_id')
            ->get();

        foreach ($pivotRecords as $record) {
            DB::table('modules')
                ->where('id', $record->module_id)
                ->update(['course_id' => $record->course_id]);
        }
    }
};
