<?php

namespace App\Providers;

use A17\Twill\Facades\TwillNavigation;
use A17\Twill\View\Components\Navigation\NavigationLink;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::requireMorphMap();

        Relation::enforceMorphMap([
            'theme' => 'App\Models\Theme',
            'theme_prompt' => 'App\Models\ThemePrompt',
        ]);

        TwillNavigation::addLink(
            NavigationLink::make()->forModule('themes')
        );
    }
}
