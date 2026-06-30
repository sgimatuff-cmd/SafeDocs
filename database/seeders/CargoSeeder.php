<?php
namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            [
                'nome'      => 'Utilizador',
                'slug'      => 'utilizador',
                'nivel'     => 1,
                'descricao' => 'Acesso básico — visualizar e descarregar ficheiros dos seus grupos.',
            ],
            [
                'nome'      => 'Operador',
                'slug'      => 'operador',
                'nivel'     => 2,
                'descricao' => 'Pode carregar ficheiros para os grupos a que pertence.',
            ],
            [
                'nome'      => 'Moderador',
                'slug'      => 'moderador',
                'nivel'     => 3,
                'descricao' => 'Pode carregar, eliminar ficheiros, gerir utilizadores e ver logs.',
            ],
            [
                'nome'      => 'Administrador',
                'slug'      => 'admin',
                'nivel'     => 4,
                'descricao' => ' Acesso total.',
            ],
        ];

        foreach ($cargos as $cargo) {
            Cargo::firstOrCreate(['slug' => $cargo['slug']], $cargo);
        }

        echo " Cargos criados: Utilizador, Operador, Moderador, Administrador\n";
    }
}
