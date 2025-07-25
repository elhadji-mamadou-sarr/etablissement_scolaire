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
        Schema::create('notes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
    $table->foreignId('cour_id')->constrained()->onDelete('cascade');
    $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
    $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
    $table->decimal('note', 5, 2);
    $table->string('semestre'); // ex: S1 ou S2
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
