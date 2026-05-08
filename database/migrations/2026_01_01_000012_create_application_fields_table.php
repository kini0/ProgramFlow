<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('section')->default('general')
                ->comment('Regroupement logique : general, parcours, projet, motivation, documents');
            $table->string('label');
            $table->string('key');
            $table->enum('type', ['text', 'textarea', 'email', 'tel', 'url', 'date', 'number',
                'select', 'multiselect', 'checkbox', 'radio', 'file', 'video'])
                ->default('text');
            $table->json('options')->nullable()->comment('Pour select/radio/checkbox');
            $table->boolean('is_required')->default(false);
            $table->string('help_text')->nullable();
            $table->json('validation_rules')->nullable();
            $table->unsignedSmallInteger('order_column')->default(0);
            $table->timestamps();

            $table->unique(['program_id', 'key']);
            $table->index(['program_id', 'order_column']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_fields');
    }
};
