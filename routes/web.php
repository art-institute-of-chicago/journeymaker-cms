<?php

use App\Http\Controllers\JsonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/json/{id}', JsonController::class);
