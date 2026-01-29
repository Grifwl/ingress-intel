<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portals', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom del portal
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('address')->nullable();
            $table->enum('type', ['home', 'work', 'weekend', 'vacation', 'other'])->default('other');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Taula pivot: agents i els seus portals sofà
        Schema::create('agent_portal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('portal_id')->constrained()->onDelete('cascade');
            $table->enum('portal_type', ['home', 'work', 'weekend', 'vacation', 'other']);
            $table->boolean('confirmed')->default(false); // Si està confirmat o és suposició
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['agent_id', 'portal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_portal');
        Schema::dropIfExists('portals');
    }
};
