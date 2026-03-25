<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') return;

        // View: DailyVisitorCount
        DB::unprepared("IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_DailyVisitorCount') DROP VIEW vw_DailyVisitorCount");
        $viewSql = file_get_contents(database_path('sql/views/vw_DailyVisitorCount.sql'));
        DB::unprepared($viewSql);

        // Stored Procedure: GetFairReport
        DB::unprepared("IF EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND name = 'usp_GetFairReport') DROP PROCEDURE usp_GetFairReport");
        $reportProcSql = file_get_contents(database_path('sql/stored_procedures/usp_GetFairReport.sql'));
        DB::unprepared($reportProcSql);

        // Stored Procedure: RecruitEmployee
        DB::unprepared("IF EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND name = 'usp_RecruitEmployee') DROP PROCEDURE usp_RecruitEmployee");
        $recruitProcSql = file_get_contents(database_path('sql/stored_procedures/usp_RecruitEmployee.sql'));
        DB::unprepared($recruitProcSql);
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') return;
        DB::unprepared("DROP VIEW IF EXISTS vw_DailyVisitorCount");
        DB::unprepared("DROP PROCEDURE IF EXISTS usp_GetFairReport");
        DB::unprepared("DROP PROCEDURE IF EXISTS usp_RecruitEmployee");
    }
};
