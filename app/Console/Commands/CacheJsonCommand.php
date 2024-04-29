<?php

namespace App\Console\Commands;

use App\Http\Resources\ThemeResource;
use App\Models\ActivityTemplate;
use App\Models\Artwork;
use App\Repositories\ThemeRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class CacheJsonCommand extends Command
{
    protected $signature = 'app:cache-json {id?}';

    protected $description = 'Cache JSON data for for the front-end.';

    public function __construct(
        private readonly ThemeRepository $themeRepository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        Artwork::cacheArtworkApiData();

        collect([
            'data.json' => 'en',
            'data-es.json' => 'es',
            'data-zh-hans.json' => 'zh',
        ])
            ->when($this->argument('id'), fn ($collection) => $collection->only($this->argument('id')))
            ->each(function ($locale, $key) {
                App::setLocale($locale);

                $data = [
                    'activityTemplates' => ActivityTemplate::select('id', 'img')->get(),
                    'themes' => ThemeResource::collection(
                        $this->themeRepository->active()->with(
                            [
                                'prompts' => fn (Builder $query) => $query->active(),
                                'prompts.artworks' => fn (Builder $query) => $query->active()->limit(8),
                            ]
                        )->get()
                    ),
                ];

                Cache::put($key, json_encode($data));
            });
    }
}
