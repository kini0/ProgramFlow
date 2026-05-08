<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 32)->unique()
                ->comment('Référence publique unique : PF-2026-000123');
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                'draft', 'submitted', 'under_review', 'shortlisted',
                'rejected', 'accepted', 'waitlisted', 'withdrawn'
            ])->default('draft');
            $table->text('motivation')->nullable();
            $table->text('project_summary')->nullable();
            $table->decimal('average_score', 5, 2)->nullable();
            $table->unsignedInteger('evaluations_count')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('decision_reason')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['program_id', 'user_id']);
            $table->index('status');
            $table->index(['program_id', 'status']);
            $table->index('submitted_at');
        });

        Schema::create('application_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('application_field_id')->constrained()->cascadeOnDelete();
            $table->longText('value')->nullable();
            $table->json('value_json')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'application_field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_responses');
        Schema::dropIfExists('applications');
    }
};
