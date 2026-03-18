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
    Schema::create('fairs', function (Blueprint $table) {
        $table->id('fair_id'); // 
        $table->foreignId('admin_id')->constrained('users', 'user_id'); // 
        $table->string('name', 150); // 
        $table->string('location', 200); // 
        $table->date('start_date'); // 
        $table->date('end_date'); // 
        $table->integer('total_stalls'); // 
        $table->string('status', 20)->default('upcoming'); // 
        $table->timestamps();
    });

    // MSSQL CHECK constraint for date integrity 
    if (DB::getDriverName() !== 'sqlite') {
        DB::statement("ALTER TABLE fairs ADD CONSTRAINT chk_fair_dates CHECK (end_date >= start_date)");
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fairs');
    }
};
