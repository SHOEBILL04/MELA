<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Implementing Issue #09: Admin Dashboard View with CTEs and Window Functions
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Drop the view if it already exists
        DB::unprepared("IF EXISTS (SELECT * FROM sys.objects WHERE type = 'V' AND name = 'vw_FairSummary')
                        DROP VIEW vw_FairSummary");

        // Load and execute the SQL from the view file
        $path = database_path('sql/views/vw_FairSummary.sql');
        
        if (file_exists($path)) {
            $sql = file_get_contents($path);
            if (!empty(trim($sql))) {
                DB::unprepared($sql);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::unprepared("DROP VIEW IF EXISTS vw_FairSummary");
    }
};
