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
        Schema::create('stalls', function (Blueprint $table) {
            $table->id('stall_id');
            $table->foreignId('fair_id')->constrained('fairs', 'fair_id');
            $table->foreignId('vendor_id')->nullable()->constrained('users', 'user_id');
            $table->string('stall_number', 20);
            $table->string('category', 50)->nullable();
            $table->integer('max_employees');
            $table->decimal('price', 10, 2);
            $table->string('status', 20)->default('available');
            $table->timestamps();
        });

        // MSSQL CHECK constraint for employee capacity
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE stalls ADD CONSTRAINT chk_max_employees CHECK (max_employees > 0)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stalls');
    }
};
