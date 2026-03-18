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
        Schema::create('employee_positions', function (Blueprint $table) {
            $table->id('position_id');
            $table->foreignId('stall_id')->constrained('stalls', 'stall_id')->onDelete('cascade');
            $table->string('title', 100);
            $table->enum('status', ['open', 'filled', 'closed'])->default('open');
            $table->decimal('salary', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_positions');
    }
};
