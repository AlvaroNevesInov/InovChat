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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuário que receberá a notificação
            $table->foreignId('mensagem_id')->constrained('mensagens')->onDelete('cascade'); // Mensagem que gerou a notificação
            $table->foreignId('sala_id')->constrained('salas')->onDelete('cascade'); // Sala onde aconteceu
            $table->string('type')->default('mention'); // Tipo: mention, dm, etc
            $table->boolean('lida')->default(false); // Se foi lida
            $table->timestamps();

            $table->index(['user_id', 'lida']); // Índice para buscar notificações não lidas rapidamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
