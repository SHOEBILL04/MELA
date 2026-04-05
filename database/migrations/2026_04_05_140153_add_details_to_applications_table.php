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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('applicant_name', 150)->nullable()->after('position_id');
            $table->unsignedTinyInteger('applicant_age')->nullable()->after('applicant_name');
            $table->string('applicant_gender', 50)->nullable()->after('applicant_age');
            $table->string('home_location', 255)->nullable()->after('applicant_gender');
            $table->string('education_status', 150)->nullable()->after('home_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_name',
                'applicant_age',
                'applicant_gender',
                'home_location',
                'education_status',
            ]);
        });
    }
};
