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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->float('ai_score', 2)->default(0);
            $table->longText('ai_feedback')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('resume_id')->constrained('resumes', 'id')->cascadeOnDelete();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies', 'id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
