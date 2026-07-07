<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Ficheiros carregados pelo admin
        Schema::create('ficheiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome_exibicao');             // nome que aparece no site
            $table->string('nome_original');             // nome original do ficheiro
            $table->string('nome_armazenado')->unique(); // nome UUID no disco
            $table->string('tipo_mime')->nullable();     // ex: application/pdf
            $table->unsignedBigInteger('tamanho')->default(0); // tamanho em bytes
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('carregado_por')->constrained('utilizadores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ficheiros');
    }
};
