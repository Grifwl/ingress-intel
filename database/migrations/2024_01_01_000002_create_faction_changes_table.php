<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faction_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->enum('from_faction', ['resistance', 'enlightened'])->nullable();
            $table->enum('to_faction', ['resistance', 'enlightened']);
            $table->date('change_date');
            $table->text('reason')->nullable(); // Per què va canviar
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faction_changes');
    }
};
