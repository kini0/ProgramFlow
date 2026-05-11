<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_session_id')->nullable()->constrained()->nullOnDelete()
                ->comment('Optionnel : rapport lié à une session précise');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable()->comment('Contenu du rapport (markdown ou HTML)');
            $table->date('activity_date')->comment('Date de l\'activité décrite');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('activity_date');
            $table->index(['program_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_reports');
    }
};
