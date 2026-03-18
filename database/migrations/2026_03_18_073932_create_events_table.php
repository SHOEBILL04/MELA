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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->foreignId('fair_id')->constrained('fairs', 'fair_id')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('users', 'user_id');
            $table->string('name', 150);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('ticket_price', 10, 2);
            $table->integer('max_capacity');
            $table->integer('tickets_sold')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
