<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
            $table->foreignId('cour_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
            $table->decimal('note', 5, 2); // Note sur 20 avec 2 décimales
            $table->enum('type_note', ['devoir', 'composition', 'examen'])->default('devoir');
            $table->float('coefficient')->default(1);
            $table->string('semestre', 20); // 'Semestre 1' ou 'Semestre 2'
            $table->date('date_evaluation');
            $table->text('commentaire')->nullable();
            $table->timestamps();

            // Contrainte unique pour limiter à 2 notes par type/semestre
            $table->unique([
                'eleve_id',
                'cour_id',
                'semestre',
                'type_note'
            ], 'note_unique_per_type');
        });

        // Index pour les requêtes fréquentes
        Schema::table('notes', function (Blueprint $table) {
            $table->index(['eleve_id', 'semestre']);
            $table->index(['cour_id', 'classroom_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');

    }
};
