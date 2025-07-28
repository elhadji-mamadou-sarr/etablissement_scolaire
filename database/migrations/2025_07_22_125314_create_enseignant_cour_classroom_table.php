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
        Schema::create('enseignant_cour_classroom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
            $table->foreignId('cour_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['enseignant_id', 'cour_id', 'classroom_id'], 'enseignant_cour_clr');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignant_cour_classroom');

    }
};
