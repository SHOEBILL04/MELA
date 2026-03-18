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

        // Drop the trigger if it already exists to avoid errors
        DB::unprepared("IF EXISTS (SELECT * FROM sys.objects WHERE type = 'TR' AND name = 'trg_PreventEventOversell')
                        DROP TRIGGER trg_PreventEventOversell");

        // Load and execute the SQL from the trigger file
        $path = database_path('sql/triggers/trg_PreventEventOversell.sql');
        
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

        DB::unprepared("DROP TRIGGER IF EXISTS trg_PreventEventOversell");
    }
};
