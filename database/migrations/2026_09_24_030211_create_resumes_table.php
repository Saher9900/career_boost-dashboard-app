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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_url');
            $table->string('contact_details');
            $table->longText('education');
            $table->longText('summary');
            $table->longText('skills');
            $table->longText('experience');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
