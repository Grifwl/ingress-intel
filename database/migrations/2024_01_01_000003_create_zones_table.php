<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la zona (ex: "Igualada Centre", "Barcelona Gràcia")
            $table->text('description')->nullable();
            // Coordenades GPS per delimitar la zona (polígon)
            $table->decimal('center_latitude', 10, 7)->nullable();
            $table->decimal('center_longitude', 10, 7)->nullable();
            $table->json('polygon_coordinates')->nullable(); // Array de coordenades per delimitar
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('ES');
            $table->timestamps();
        });

        // Taula pivot: agents i zones on juguen
        Schema::create('agent_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'occasionally'])->default('occasionally');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['agent_id', 'zone_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_zone');
        Schema::dropIfExists('zones');
    }
};
