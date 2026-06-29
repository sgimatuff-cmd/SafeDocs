<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Grupos de utilizadores (ex: "Grupo Geral", "Turma A")
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        // Tabela intermédia: quais utilizadores pertencem a que grupos
        Schema::create('grupo_utilizador', function (Blueprint $table) {
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('utilizador_id')->constrained('utilizadores')->onDelete('cascade');
            $table->primary(['grupo_id', 'utilizador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_utilizador');
        Schema::dropIfExists('grupos');
    }
};
