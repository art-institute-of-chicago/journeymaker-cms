<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThemeResource;
use App\Models\ActivityTemplate;
use App\Models\Artwork;
use App\Repositories\ThemeRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class JsonController extends Controller
{
    public function __construct(
        private readonly ThemeRepository $themeRepository
    ) {
    }

    public function __invoke($id)
    {
        $locale = match ($id) {
            'data-es' => 'es',
            'data-zh-hans' => 'zh',
            default => 'en',
        };

        App::setLocale($locale);

        Artwork::cacheArtworkApiData();

        return response()->json([
            'activityTemplates' => ActivityTemplate::select('id', 'img')->get(),
            'themes' => ThemeResource::collection(
                $this->themeRepository->published()->with(
                    [
                        'prompts' => fn (Builder $query) => $query
                            ->published(),
                        'prompts.artworks' => fn (Builder $query) => $query
                            ->whereRelation('artwork', 'is_on_view', true)
                            ->whereRelation('artwork', 'published', true)
                            ->limit(8),
                    ]
                )->get()
            ),
        ]);
    }
}
