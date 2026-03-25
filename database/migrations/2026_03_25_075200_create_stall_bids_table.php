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
        Schema::create('stall_bids', function (Blueprint $table) {
            $table->id('bid_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('stall_id');
            $table->decimal('bid_amount', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('vendor_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('stall_id')->references('stall_id')->on('stalls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_bids');
    }
};
