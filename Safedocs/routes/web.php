<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SessaoController;
use App\Http\Controllers\Auth\RegistoController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\FicheiroController;
use App\Http\Controllers\MeuGrupoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GrupoController;
use App\Http\Controllers\Admin\LogController;

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::middleware('guest')->group(function () {
    Route::get('/entrar',   [SessaoController::class, 'mostrar'])->name('entrar');
    Route::post('/entrar',  [SessaoController::class, 'entrar']);
    Route::get('/registar', [RegistoController::class, 'mostrar'])->name('registar');
    Route::post('/registar', [RegistoController::class, 'registar']);
});

Route::post('/sair', [SessaoController::class, 'sair'])
    ->middleware('auth')->name('sair');

Route::middleware('auth')->group(function () {

    Route::get('/painel', [PainelController::class, 'mostrar'])->name('painel');

    Route::prefix('ficheiros')->name('ficheiros.')->group(function () {
        Route::get('/',                     [FicheiroController::class, 'listar'])->name('listar');
        Route::get('/carregar',             [FicheiroController::class, 'criar'])->name('criar');
        Route::post('/',                    [FicheiroController::class, 'guardar'])->name('guardar');
        Route::get('/{ficheiro}/descarregar', [FicheiroController::class, 'descarregar'])->name('descarregar');
        Route::delete('/{ficheiro}',         [FicheiroController::class, 'eliminar'])->name('eliminar')->middleware('cargo:operador,admin');
    });

    // Área do operador: gestão do(s) seu(s) próprio(s) grupo(s) ("admin do grupo")
    Route::prefix('meu-grupo')->name('meu-grupo.')->middleware('cargo:operador,admin')->group(function () {
        Route::get('/',                                 [MeuGrupoController::class, 'mostrar'])->name('mostrar');
        Route::post('/{grupo}/membros',                  [MeuGrupoController::class, 'adicionarMembro'])->name('membros.adicionar');
        Route::delete('/{grupo}/membros/{utilizador}',    [MeuGrupoController::class, 'removerMembro'])->name('membros.remover');
    });

    Route::prefix('administracao')->name('admin.')->group(function () {

        Route::middleware('cargo:admin')->group(function () {
            Route::get('/utilizadores',                                     [AdminController::class, 'utilizadores'])->name('utilizadores');
            Route::post('/utilizadores/{utilizador}/promover',                 [AdminController::class, 'promover'])->name('utilizadores.promover');
            Route::post('/utilizadores/{utilizador}/promover-operador',        [AdminController::class, 'promoverOperador'])->name('utilizadores.promover-operador');
            Route::delete('/utilizadores/{utilizador}/eliminar',             [AdminController::class, 'eliminar'])->name('utilizadores.eliminar');
            Route::post('/utilizadores/{utilizador}/grupo',                 [AdminController::class, 'adicionarAoGrupo'])->name('utilizadores.grupo.adicionar');
            Route::delete('/utilizadores/{utilizador}/grupo/{grupo}/remover',  [AdminController::class, 'removerDoGrupo'])->name('utilizadores.grupo.remover');
            Route::post('/utilizadores/{utilizador}/cargos',                 [AdminController::class, 'adicionarCargo'])->name('utilizadores.cargos.adicionar');
            Route::delete('/utilizadores/{utilizador}/cargos/{cargo}',        [AdminController::class, 'removerCargo'])->name('utilizadores.cargos.remover');
            Route::get('/logs',                                             [LogController::class, 'listar'])->name('logs');
        });

        Route::middleware('admin')->group(function () {
            Route::get('/grupos',           [GrupoController::class, 'listar'])->name('grupos');
            Route::post('/grupos',           [GrupoController::class, 'criar'])->name('grupos.criar');
            Route::delete('/grupos/{grupo}', [GrupoController::class, 'eliminar'])->name('grupos.eliminar');
        });
    });
});

Route::fallback(fn() => redirect()->route('inicio'));
