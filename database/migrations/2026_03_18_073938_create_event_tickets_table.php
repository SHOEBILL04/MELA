<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id('event_ticket_id');
            $table->foreignId('visitor_id')->constrained('users', 'user_id')->onDelete('no action');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->foreignId('fair_ticket_id')->constrained('fair_tickets', 'ticket_id')->onDelete('no action');
            $table->timestamp('purchase_date')->useCurrent();
            $table->decimal('ticket_price', 10, 2);
            $table->string('qr_code', 100)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
