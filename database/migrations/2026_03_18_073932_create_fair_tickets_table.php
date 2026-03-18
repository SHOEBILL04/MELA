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
        Schema::create('fair_tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->foreignId('visitor_id')->constrained('users', 'user_id')->onDelete('no action');
            $table->foreignId('fair_id')->constrained('fairs', 'fair_id')->onDelete('cascade');
            $table->foreignId('day_id')->constrained('fair_days', 'day_id')->onDelete('no action');
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
        Schema::dropIfExists('fair_tickets');
    }
};
