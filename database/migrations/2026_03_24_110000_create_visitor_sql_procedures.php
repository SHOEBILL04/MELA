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
        DB::unprepared("IF OBJECT_ID('usp_BuyFairTicket', 'P') IS NOT NULL DROP PROCEDURE usp_BuyFairTicket");
        DB::unprepared("IF OBJECT_ID('usp_BuyEventTicket', 'P') IS NOT NULL DROP PROCEDURE usp_BuyEventTicket");

        // Execute SQL scripts from file system per strict policy
        $fairPath = database_path('sql/stored_procedures/usp_BuyFairTicket.sql');
        if (file_exists($fairPath)) {
            DB::unprepared(file_get_contents($fairPath));
        }

        $eventPath = database_path('sql/stored_procedures/usp_BuyEventTicket.sql');
        if (file_exists($eventPath)) {
            DB::unprepared(file_get_contents($eventPath));
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

        DB::unprepared("IF OBJECT_ID('usp_BuyFairTicket', 'P') IS NOT NULL DROP PROCEDURE usp_BuyFairTicket");
        DB::unprepared("IF OBJECT_ID('usp_BuyEventTicket', 'P') IS NOT NULL DROP PROCEDURE usp_BuyEventTicket");
    }
};
