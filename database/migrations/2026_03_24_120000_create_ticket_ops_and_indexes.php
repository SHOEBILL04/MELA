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
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Drop existing to be safe
        DB::unprepared("IF OBJECT_ID('vw_VisitorTickets', 'V') IS NOT NULL DROP VIEW vw_VisitorTickets");
        DB::unprepared("IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_GenerateTicketQR_Fair') DROP TRIGGER trg_GenerateTicketQR_Fair");
        DB::unprepared("IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_GenerateTicketQR_Event') DROP TRIGGER trg_GenerateTicketQR_Event");

        // Execute View
        $viewPath = database_path('sql/views/vw_VisitorTickets.sql');
        if (file_exists($viewPath)) {
            DB::unprepared(file_get_contents($viewPath));
        }

        // Execute Triggers
        $trgFair = database_path('sql/triggers/trg_GenerateTicketQR_Fair.sql');
        if (file_exists($trgFair)) {
            DB::unprepared(file_get_contents($trgFair));
        }

        $trgEvent = database_path('sql/triggers/trg_GenerateTicketQR_Event.sql');
        if (file_exists($trgEvent)) {
            DB::unprepared(file_get_contents($trgEvent));
        }

        // Task 5: Add non-clustered indexes using Laravel Schema standard
        Schema::table('fair_tickets', function (Blueprint $table) {
            $table->index('visitor_id', 'idx_fair_tickets_visitor');
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $table->index('event_id', 'idx_event_tickets_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::unprepared("IF OBJECT_ID('vw_VisitorTickets', 'V') IS NOT NULL DROP VIEW vw_VisitorTickets");
        DB::unprepared("IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_GenerateTicketQR_Fair') DROP TRIGGER trg_GenerateTicketQR_Fair");
        DB::unprepared("IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_GenerateTicketQR_Event') DROP TRIGGER trg_GenerateTicketQR_Event");

        Schema::table('fair_tickets', function (Blueprint $table) {
            $table->dropIndex('idx_fair_tickets_visitor');
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $table->dropIndex('idx_event_tickets_event');
        });
    }
};
