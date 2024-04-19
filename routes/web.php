<?php

use App\Http\Controllers\JsonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/sites/default/files/json/{id}.json', JsonController::class);
