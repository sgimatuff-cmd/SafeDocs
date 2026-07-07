<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * O cargo "Moderador" deixou de existir. Qualquer utilizador que o
     * tivesse passa a ter o cargo "Operador" em vez disso.
     */
    public function up(): void
    {
        $moderador = DB::table('cargos')->where('slug', 'moderador')->first();

        if (!$moderador) {
            return;
        }

        $operador = DB::table('cargos')->where('slug', 'operador')->first();

        if ($operador) {
            $utilizadoresComModerador = DB::table('cargo_utilizador')
                ->where('cargo_id', $moderador->id)
                ->pluck('utilizador_id');

            foreach ($utilizadoresComModerador as $utilizadorId) {
                $jaTemOperador = DB::table('cargo_utilizador')
                    ->where('cargo_id', $operador->id)
                    ->where('utilizador_id', $utilizadorId)
                    ->exists();

                if (!$jaTemOperador) {
                    DB::table('cargo_utilizador')->insert([
                        'cargo_id'       => $operador->id,
                        'utilizador_id'  => $utilizadorId,
                    ]);
                }
            }
        }

        DB::table('cargo_utilizador')->where('cargo_id', $moderador->id)->delete();
        DB::table('cargos')->where('id', $moderador->id)->delete();
    }

    public function down(): void
    {
        DB::table('cargos')->insertOrIgnore([
            'nome'       => 'Moderador',
            'slug'       => 'moderador',
            'nivel'      => 3,
            'descricao'  => 'Pode carregar, eliminar ficheiros, gerir utilizadores e ver logs.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
