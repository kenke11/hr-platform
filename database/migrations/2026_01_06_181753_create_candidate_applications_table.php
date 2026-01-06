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
        Schema::create('candidate_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('vacancy_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();

            $table->string('position')->nullable(); // desired position / role

            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();

            $table->string('cv_path');

            $table->text('cover_letter')->nullable();

            $table->enum('status', [
                'new',
                'reviewed',
                'shortlisted',
                'rejected',
            ])->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_applications');
    }
};
