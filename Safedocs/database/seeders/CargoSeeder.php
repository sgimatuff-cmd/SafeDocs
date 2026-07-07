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
                'descricao' => 'Acesso básico - visualizar e descarregar ficheiros dos seus grupos.',
            ],
            [
                'nome'      => 'Operador',
                'slug'      => 'operador',
                'nivel'     => 2,
                'descricao' => 'Pode carregar e eliminar ficheiros, e gerir o seu próprio grupo.',
            ],
            [
                'nome'      => 'Administrador',
                'slug'      => 'admin',
                'nivel'     => 3,
                'descricao' => 'Acesso total.',
            ],
        ];

        foreach ($cargos as $cargo) {
            Cargo::firstOrCreate(['slug' => $cargo['slug']], $cargo);
        }

        echo " Cargos criados: Utilizador, Operador, Administrador\n";
    }
}
