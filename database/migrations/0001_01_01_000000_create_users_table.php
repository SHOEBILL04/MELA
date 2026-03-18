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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Primary Key, IDENTITY
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Laravel uses this for bcrypt password_hash
            $table->enum('role', ['admin', 'vendor', 'employee', 'visitor'])->default('visitor'); // admin / vendor / employee / visitor
            $table->string('phone', 20)->nullable();
            $table->rememberToken();
            $table->timestamps(); // Generates created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
