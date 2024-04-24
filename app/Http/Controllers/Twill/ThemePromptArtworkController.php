<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;

class ThemePromptArtworkController extends ModuleController
{
    protected $moduleName = 'themePromptArtworks';

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->disableBulkEdit();
        $this->disableBulkPublish();
        $this->disableBulkRestore();
        $this->disableBulkForceDelete();
    }
}
