<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cette migration est intentionnellement légère : elle s'assure que la
 * structure existante est cohérente. Le gros changement métier (ajout des
 * sections standard) est porté par le seeder ProgramService::seedStandardFields().
 */
return new class extends Migration
{
    public function up(): void
    {
        // Aucune modification de schéma nécessaire.
        // Toutes les colonnes (options json, validation_rules json, section, etc.)
        // existent déjà dans la migration 000012.
    }

    public function down(): void
    {
        //
    }
};
