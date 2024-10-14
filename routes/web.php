<?php

use Illuminate\Support\Facades\Route;

// Registrar a rota raiz para o Filament
Route::get('/', function () {
    return redirect('/admin'); // Ou coloque a rota do Filament diretamente
});
