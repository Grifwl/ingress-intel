<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Anomaly", "Conflicte", "Op conjunta"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icona per la UI
            $table->string('color')->nullable(); // Color per la UI
            $table->boolean('is_positive')->default(true); // Si és positiu o negatiu
            $table->timestamps();
        });

        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('interaction_type_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Títol de la interacció
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('location')->nullable(); // Ubicació de la interacció
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->text('outcome')->nullable(); // Resultat de la interacció
            $table->integer('impact_level')->nullable(); // 1-5, impacte de la interacció
            $table->json('other_agents')->nullable(); // Altres agents que hi van participar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactions');
        Schema::dropIfExists('interaction_types');
    }
};
