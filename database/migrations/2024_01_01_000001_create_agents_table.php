<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('codename')->unique(); // Nom d'agent al joc
            $table->string('real_name')->nullable(); // Nom real si el saps
            $table->enum('current_faction', ['resistance', 'enlightened']);
            $table->string('telegram_username')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('level')->nullable(); // Nivell al joc (1-16)
            $table->date('first_contact_date')->nullable(); // Primera vegada que el vas conèixer
            $table->text('notes')->nullable(); // Notes generals
            $table->boolean('is_active')->default(true); // Si segueix jugant
            $table->timestamps();
            $table->softDeletes(); // Per poder "eliminar" sense perdre historial
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
