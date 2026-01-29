<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secondary_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('primary_agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('codename'); // Nom del compte secundari
            $table->enum('faction', ['resistance', 'enlightened']);
            $table->integer('level')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned', 'suspected'])->default('suspected');
            $table->enum('certainty', ['confirmed', 'very_likely', 'likely', 'suspected'])->default('suspected');
            $table->text('evidence')->nullable(); // Evidències que és un multi
            $table->date('first_spotted')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secondary_accounts');
    }
};
