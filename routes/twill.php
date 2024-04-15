<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\ArtworkController;
use Illuminate\Support\Facades\Route;

TwillRoutes::module('themes');
TwillRoutes::module('themes.prompts');

Route::get('admin/artworks/query', [ArtworkController::class, 'queryArtwork']);

Route::get(
    'collectionObjects/augment/{datahub_id}',
    [ArtworkController::class, 'augment']
)->name('artworks.augment');

TwillRoutes::module('artworks');
