<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Implementing Issue #03: Performance Optimization via Non-Clustered Indexes
     */
    public function up(): void
    {
        // Fairs table indexes
        Schema::table('fairs', function (Blueprint $table) {
            $table->index('admin_id', 'ix_fairs_admin_id');
            $table->index('status', 'ix_fairs_status');
        });

        // Stalls table indexes (Crucial for Vendor browsing)
        Schema::table('stalls', function (Blueprint $table) {
            $table->index('fair_id', 'ix_stalls_fair_id');
            $table->index('vendor_id', 'ix_stalls_vendor_id');
            $table->index('status', 'ix_stalls_status');
        });

        // Events table indexes
        Schema::table('events', function (Blueprint $table) {
            $table->index('fair_id', 'ix_events_fair_id');
            $table->index('vendor_id', 'ix_events_vendor_id');
        });

        // Applications table indexes (Crucial for Employee/Vendor matching)
        Schema::table('applications', function (Blueprint $table) {
            $table->index('employee_id', 'ix_apps_employee_id');
            $table->index('position_id', 'ix_apps_position_id');
            $table->index('status', 'ix_apps_status');
        });

        // Ticket tables (High-volume tables)
        Schema::table('fair_tickets', function (Blueprint $table) {
            $table->index('visitor_id', 'ix_fair_tickets_visitor_id');
            $table->index('fair_id', 'ix_fair_tickets_fair_id');
            $table->index('day_id', 'ix_fair_tickets_day_id');
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $table->index('visitor_id', 'ix_event_tickets_visitor_id');
            $table->index('event_id', 'ix_event_tickets_event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fairs', fn (Blueprint $table) => $table->dropIndex('ix_fairs_admin_id'));
        Schema::table('fairs', fn (Blueprint $table) => $table->dropIndex('ix_fairs_status'));
        
        Schema::table('stalls', fn (Blueprint $table) => $table->dropIndex('ix_stalls_fair_id'));
        Schema::table('stalls', fn (Blueprint $table) => $table->dropIndex('ix_stalls_vendor_id'));
        Schema::table('stalls', fn (Blueprint $table) => $table->dropIndex('ix_stalls_status'));

        Schema::table('events', fn (Blueprint $table) => $table->dropIndex('ix_events_fair_id'));
        Schema::table('events', fn (Blueprint $table) => $table->dropIndex('ix_events_vendor_id'));

        Schema::table('applications', fn (Blueprint $table) => $table->dropIndex('ix_apps_employee_id'));
        Schema::table('applications', fn (Blueprint $table) => $table->dropIndex('ix_apps_position_id'));
        Schema::table('applications', fn (Blueprint $table) => $table->dropIndex('ix_apps_status'));

        Schema::table('fair_tickets', fn (Blueprint $table) => $table->dropIndex('ix_fair_tickets_visitor_id'));
        Schema::table('fair_tickets', fn (Blueprint $table) => $table->dropIndex('ix_fair_tickets_fair_id'));
        Schema::table('fair_tickets', fn (Blueprint $table) => $table->dropIndex('ix_fair_tickets_day_id'));

        Schema::table('event_tickets', fn (Blueprint $table) => $table->dropIndex('ix_event_tickets_visitor_id'));
        Schema::table('event_tickets', fn (Blueprint $table) => $table->dropIndex('ix_event_tickets_event_id'));
    }
};
