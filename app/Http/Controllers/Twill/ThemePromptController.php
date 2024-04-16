<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Breadcrumbs\NestedBreadcrumbs;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use App\Repositories\ThemeRepository;

class ThemePromptController extends BaseModuleController
{
    protected $moduleName = 'themes.prompts';

    protected $modelName = 'ThemePrompt';

    protected function setUpController(): void
    {
        $this->disablePermalink();

        if (request('theme')) {
            $this->setBreadcrumbs(
                NestedBreadcrumbs::make()
                    ->forParent(
                        parentModule: 'themes',
                        module: $this->moduleName,
                        activeParentId: request('theme'),
                        repository: ThemeRepository::class
                    )
                    ->label('Prompts')
            );
        }
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('subtitle')->label('Subtitle')->translatable()
        );

        return $form;
    }
}
