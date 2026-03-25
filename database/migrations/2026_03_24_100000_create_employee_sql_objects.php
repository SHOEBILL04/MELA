<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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

        // Drop previous objects if they exist
        DB::unprepared("IF OBJECT_ID('vw_AvailablePositions', 'V') IS NOT NULL DROP VIEW vw_AvailablePositions");
        DB::unprepared("IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_PreventDuplicateApplication') DROP TRIGGER trg_PreventDuplicateApplication");

        // 1. Create View for Available Positions (3-Table Join)
        $viewPath = database_path('sql/views/vw_AvailablePositions.sql');
        if (file_exists($viewPath)) {
            DB::unprepared(file_get_contents($viewPath));
        }

        // 2. Create Trigger for application duplicate check
        $triggerPath = database_path('sql/triggers/trg_PreventDuplicateApplication.sql');
        if (file_exists($triggerPath)) {
            DB::unprepared(file_get_contents($triggerPath));
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

        DB::unprepared("IF OBJECT_ID('vw_AvailablePositions', 'V') IS NOT NULL DROP VIEW vw_AvailablePositions");
        DB::unprepared("IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_PreventDuplicateApplication') DROP TRIGGER trg_PreventDuplicateApplication");
    }
};
