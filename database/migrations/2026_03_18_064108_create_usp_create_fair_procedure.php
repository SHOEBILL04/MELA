<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // 1. Drop the procedure if it already exists to avoid "Already Exists" errors
        DB::unprepared("IF EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND name = 'usp_CreateFair')
                        DROP PROCEDURE usp_CreateFair");

        // 2. Load the SQL content from your folder structure [cite: 17, 57]
        $path = database_path('sql/stored_procedures/usp_CreateFair.sql');
        
        if (file_exists($path)) {
            $sql = file_get_contents($path);
            if (!empty(trim($sql))) {
                DB::unprepared($sql); // This actually creates the procedure in MSSQL
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

        // Remove the logic if you rollback the migration
        DB::unprepared("DROP PROCEDURE IF EXISTS usp_CreateFair");
    }
};