<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThemeCollection;
use App\Models\ActivityTemplate;
use App\Repositories\ThemeRepository;
use Illuminate\Support\Facades\App;

class JsonController extends Controller
{
    public function __invoke($id, ThemeRepository $themeRepository)
    {
        $locale = match ($id) {
            'data-es' => 'es',
            'data-zh-hans' => 'zh',
            default => 'en',
        };

        App::setLocale($locale);

        $data = [
            'activityTemplates' => ActivityTemplate::select('id', 'img')->get(),
            'themes' => new ThemeCollection($themeRepository->with(['prompts.artworks'])->get()),
        ];

        return response()->json($data);
    }
}
