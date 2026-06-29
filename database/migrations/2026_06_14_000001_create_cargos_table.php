<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('nivel')->default(1);
            $table->string('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('cargo_utilizador', function (Blueprint $table) {
            $table->foreignId('utilizador_id')->constrained('utilizadores')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');
            $table->primary(['utilizador_id', 'cargo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_utilizador');
        Schema::dropIfExists('cargos');
    }
};
