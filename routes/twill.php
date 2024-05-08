<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\ApiLogController;
use App\Http\Controllers\Twill\ArtworkController;
use App\Http\Controllers\Twill\DirectoryController;
use Illuminate\Support\Facades\Route;

TwillRoutes::module('themes');
TwillRoutes::module('themes.prompts');

Route::get('admin/artworks/query', [ArtworkController::class, 'queryArtwork']);

TwillRoutes::module('artworks');

TwillRoutes::module('themePromptArtworks');

Route::get('directory', DirectoryController::class)
    ->name('directory');

Route::get('api-log', ApiLogController::class)
    ->name('api-log');
