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
        Schema::create('fair_days', function (Blueprint $table) {
            $table->id('day_id');
            $table->foreignId('fair_id')->constrained('fairs', 'fair_id')->onDelete('cascade');
            $table->date('day_date');
            $table->integer('max_visitors')->default(1000);
            $table->integer('visitors_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fair_days');
    }
};
