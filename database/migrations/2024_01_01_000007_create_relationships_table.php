<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relationship_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Parella", "Familiar", "Amic"
            $table->string('slug')->unique();
            $table->boolean('is_symmetric')->default(true); // Si A és amic de B, B és amic de A
            $table->string('reverse_name')->nullable(); // Per relacions asimètriques (ex: "Pare" <-> "Fill")
            $table->timestamps();
        });

        Schema::create('agent_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade'); // Agent A
            $table->foreignId('related_agent_id')->constrained('agents')->onDelete('cascade'); // Agent B
            $table->foreignId('relationship_type_id')->constrained()->onDelete('cascade');
            $table->enum('certainty', ['confirmed', 'very_likely', 'likely', 'suspected'])->default('suspected');
            $table->text('notes')->nullable();
            $table->date('since_date')->nullable(); // Des de quan es coneixen
            $table->timestamps();
            
            // Evitar duplicats
            $table->unique(['agent_id', 'related_agent_id', 'relationship_type_id'], 'unique_relationship');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_relationships');
        Schema::dropIfExists('relationship_types');
    }
};
