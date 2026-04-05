<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlsrv') {
            // Note: DB::unprepared doesn't handle 'GO' statements well in Laravel, 
            // so we will split the SQL file contents by 'GO' and execute each part.
            $sqlContents = file_get_contents(database_path('sql/roxy_objects.sql'));
            
            $statements = array_filter(
                array_map('trim', explode('GO', $sqlContents))
            );

            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    DB::unprepared($statement);
                }
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlsrv') {
            DB::unprepared("DROP INDEX IF EXISTS idx_event_tickets_event ON event_tickets");
            DB::unprepared("DROP INDEX IF EXISTS idx_fair_tickets_visitor ON fair_tickets");

            DB::unprepared("DROP VIEW IF EXISTS vw_AvailablePositions");
            DB::unprepared("DROP VIEW IF EXISTS vw_VisitorTickets");

            DB::unprepared("DROP TRIGGER IF EXISTS trg_GenerateEventTicketQR");
            DB::unprepared("DROP TRIGGER IF EXISTS trg_GenerateFairTicketQR");
            DB::unprepared("DROP TRIGGER IF EXISTS trg_PreventDuplicateApplication");

            DB::unprepared("DROP PROCEDURE IF EXISTS usp_BuyEventTicket");
            DB::unprepared("DROP PROCEDURE IF EXISTS usp_BuyFairTicket");
        }
    }
};
