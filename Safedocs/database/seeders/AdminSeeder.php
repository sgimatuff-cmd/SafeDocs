<?php
namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Grupo;
use App\Models\Utilizador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $grupoGeral = Grupo::create(['nome' => 'Grupo Geral']);

        $cargoUtilizador = Cargo::where('slug', 'utilizador')->first();
        $cargoAdmin      = Cargo::where('slug', 'admin')->first();

        $admin = Utilizador::create([
            'nome'            => 'Administrador',
            'email'           => 'admin@safedocs.pt',
            'palavra_passe'   => Hash::make('admin123'),
            'e_administrador' => true,
        ]);

        $admin->grupos()->attach($grupoGeral->id);
        $admin->cargos()->attach([$cargoUtilizador->id, $cargoAdmin->id]);

        echo "Grupo Geral criado\n";
        echo "Admin criado: admin@safedocs.pt / admin123\n";
    }
}
