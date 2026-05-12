<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the unique index exists before trying to drop it
        $indexExists = collect(DB::select('SHOW INDEX FROM modules WHERE Column_name = ?', ['course_code']))
            ->contains(function ($index) {
                return $index->Non_unique == 0; // 0 means unique
            });

        if ($indexExists) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropUnique(['course_code']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->unique('course_code');
        });
    }
};
