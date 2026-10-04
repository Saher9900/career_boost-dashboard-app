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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company');
            $table->string('description');
            $table->string('location');
            $table->string('salary');
            $table->enum('type', ['full_time', 'contract', 'remote', 'hybrid'])->default('full_time');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('company_id')->constrained('companies', 'id')->cascadeOnDelete();
            $table->foreignId('job_category_id')->nullable()->constrained('job_categories', 'id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
