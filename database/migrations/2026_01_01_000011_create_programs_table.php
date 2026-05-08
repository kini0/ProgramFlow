<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_image_path')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('objectives')->nullable();
            $table->longText('eligibility')->nullable();
            $table->unsignedSmallInteger('seats')->default(0)
                ->comment('Nombre de participantes prévues');
            $table->date('application_opens_at')->nullable();
            $table->date('application_closes_at')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->enum('status', ['draft', 'published', 'open', 'review', 'selection', 'active', 'completed', 'archived'])
                ->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->json('settings')->nullable()
                ->comment('Paramètres divers : couleurs, allow_video, etc.');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index(['application_opens_at', 'application_closes_at']);
        });

        Schema::create('partner_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable()->comment('financier, technique, jury, etc.');
            $table->timestamps();

            $table->unique(['program_id', 'partner_id']);
        });

        Schema::create('program_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['organizer', 'jury', 'mentor', 'speaker', 'participant'])->default('participant');
            $table->timestamps();

            $table->unique(['program_id', 'user_id', 'role']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_user');
        Schema::dropIfExists('partner_program');
        Schema::dropIfExists('programs');
    }
};
