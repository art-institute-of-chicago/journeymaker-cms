<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class ThemePromptArtworkController extends BaseModuleController
{
    protected $moduleName = 'themePromptArtworks';

    protected function setUpController(): void
    {
        $this->disablePermalink();
    }
}
