<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('weight')->default(1)
                ->comment('Coefficient utilisé dans le calcul du score pondéré');
            $table->unsignedSmallInteger('max_score')->default(20);
            $table->unsignedSmallInteger('order_column')->default(0);
            $table->timestamps();

            $table->index(['program_id', 'order_column']);
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jury_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['assigned', 'in_progress', 'submitted'])->default('assigned');
            $table->text('comment')->nullable();
            $table->decimal('total_score', 6, 2)->nullable();
            $table->decimal('weighted_score', 6, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'jury_id']);
            $table->index('status');
        });

        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluation_criterion_id')->constrained('evaluation_criteria')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_id', 'evaluation_criterion_id'], 'eval_score_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_scores');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('evaluation_criteria');
    }
};
