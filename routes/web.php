<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FicheiroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GrupoController;
use App\Http\Controllers\Admin\LogController;

# ======================================================================
# ROTAS DE MANUTENÇÃO NO RENDER (Remover após uso!)
# ======================================================================

Route::get('/correr-migracoes-supabase', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return '<h1>Sucesso!</h1><p>Migrações executadas.</p>';
    } catch (\Exception $e) {
        return '<h1>Erro:</h1><pre>' . $e->getMessage() . '</pre>';
    }
});

Route::get('/correr-seed-supabase', function () {
    try {
        // Roda o DatabaseSeeder completo. 
        // Se quiseres um específico, usa: Artisan::call('db:seed', ['--class' => 'NomeDoSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return '<h1>Sucesso!</h1><p>Seeder executado com sucesso no Supabase.</p>';
    } catch (\Exception $e) {
        return '<h1>Erro no Seeder:</h1><pre>' . $e->getMessage() . '</pre>';
    }
});

# ======================================================================

Route::get('/', function () {
    return view('welcome');
})->name('inicio');

Route::middleware('guest')->group(function () {
    Route::get('/entrar',   [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/entrar',  [AuthenticatedSessionController::class, 'store']);
    Route::get('/registar', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registar', [RegisteredUserController::class, 'store']);

    Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::post('/sair', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('ficheiros')->name('ficheiros.')->group(function () {
        Route::get('/',                     [FicheiroController::class, 'index'])->name('index');
        Route::get('/carregar',             [FicheiroController::class, 'create'])->name('create')->middleware('cargo:operador,moderador,admin');
        Route::post('/',                    [FicheiroController::class, 'store'])->name('store')->middleware('cargo:operador,moderador,admin');
        Route::get('/{ficheiro}/descarregar', [FicheiroController::class, 'download'])->name('download');
        Route::delete('/{ficheiro}',         [FicheiroController::class, 'destroy'])->name('destroy')->middleware('cargo:moderador,admin');
    });

    Route::prefix('administracao')->name('admin.')->group(function () {

        Route::middleware('cargo:moderador,admin')->group(function () {
            Route::get('/utilizadores',                                     [AdminController::class, 'utilizadores'])->name('utilizadores');
            Route::post('/utilizadores/{utilizador}/promover',                 [AdminController::class, 'promover'])->name('utilizadores.promover');
            Route::delete('/utilizadores/{utilizador}/eliminar',             [AdminController::class, 'eliminar'])->name('utilizadores.eliminar');
            Route::post('/utilizadores/{utilizador}/grupo',                 [AdminController::class, 'adicionarAoGrupo'])->name('utilizadores.grupo.adicionar');
            Route::delete('/utilizadores/{utilizador}/grupo/{grupo}/remover',  [AdminController::class, 'removerDoGrupo'])->name('utilizadores.grupo.remover');
            Route::post('/utilizadores/{utilizador}/cargos',                 [AdminController::class, 'adicionarCargo'])->name('utilizadores.cargos.adicionar');
            Route::delete('/utilizadores/{utilizador}/cargos/{cargo}',        [AdminController::class, 'removerCargo'])->name('utilizadores.cargos.remover');
            Route::get('/logs',                                             [LogController::class, 'index'])->name('logs');
        });

        Route::middleware('admin')->group(function () {
            Route::get('/grupos',           [GrupoController::class, 'index'])->name('grupos');
            Route::post('/grupos',           [GrupoController::class, 'store'])->name('grupos.store');
            Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');
        });
    });
});

Route::fallback(fn() => redirect()->route('inicio'));