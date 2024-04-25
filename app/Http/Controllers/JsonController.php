<?php

namespace App\Http\Controllers;

use App\Repositories\ThemeRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class JsonController extends Controller
{
    public function __construct(
        private readonly ThemeRepository $themeRepository
    ) {
    }

    public function __invoke($id)
    {
        return response(
            Cache::get($id, fn () => $this->cache($id)),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    private function cache(string $id)
    {
        Artisan::call('app:cache-json', ['id' => $id]);

        return Cache::get($id);
    }
}
