<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Breadcrumbs\NestedBreadcrumbs;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Fieldsets;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\InlineRepeater;
use App\Models\ActivityTemplate;
use App\Repositories\ThemeRepository;
use App\Support\Forms\Fields\Hidden;

class ThemePromptController extends ModuleController
{
    protected $moduleName = 'themes.prompts';

    protected $modelName = 'ThemePrompt';

    protected $previewView = 'site.prompt';

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->disableBulkEdit();
        $this->disableBulkPublish();
        $this->disableBulkRestore();
        $this->disableBulkForceDelete();
        $this->disableSortable();
        $this->enableReorder();

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

    public function getSideFieldsets(TwillModelContract $model): Form
    {
        return parent::getSideFieldsets($model)
            ->withFieldSets(new Fieldsets([
                Fieldset::make()->title('Prompts')->id('prompts')->fields([
                    BladePartial::make()
                        ->view('forms.prompts')
                        ->withAdditionalParams([
                            'theme' => $model->theme,
                            'currentPromptId' => $model->id,
                        ]),
                ]),
            ]));
    }

    public function getForm(TwillModelContract $model): Form
    {
        return parent::getForm($model)
            ->withFieldSets(new Fieldsets([
                Fieldset::make()
                    ->title('Content')
                    ->id('content')
                    ->fields([
                        Input::make()
                            ->name('title')
                            ->maxLength(21 + 5)
                            ->note('Limit is 21 characters + 5 for padding.')
                            ->translatable(),
                        Input::make()
                            ->name('subtitle')
                            ->maxLength(100 + 10)
                            ->note('Limit is 100 characters + 10 for padding.')
                            ->label('Subtitle')
                            ->translatable(),
                    ]),
                Fieldset::make()
                    ->title('Artworks')
                    ->id('artworks')
                    ->fields([
                        InlineRepeater::make()
                            ->label('Artwork')
                            ->name('artwork')
                            ->hideTitlePrefix()
                            ->titleField('title')
                            ->allowBrowser()
                            ->fields([
                                Hidden::make()
                                    ->name('title'),
                                Browser::make()
                                    ->name('artwork')
                                    ->label('Artwork')
                                    ->modules(['artworks'])
                                    ->max(1)
                                    ->required(),
                                Input::make()
                                    ->type('textarea')
                                    ->name('detail_narrative')
                                    ->maxLength(100 + 10)
                                    ->note('Limit is 100 characters + 10 for padding.')
                                    ->label('Detail Narrative (Interface)')
                                    ->translatable(),
                                Input::make()
                                    ->type('textarea')
                                    ->name('viewing_description')
                                    ->maxLength(110 + 10)
                                    ->note('Limit is 110 characters + 10 for padding.')
                                    ->label('Look Again (Journey Guide)')
                                    ->translatable(),
                                Select::make()
                                    ->name('activity_template')
                                    ->label('Activity Template (Journey Guide)')
                                    ->options(
                                        ActivityTemplate::all()->map(fn ($template) => [
                                            'value' => $template->id,
                                            'label' => $template->label,
                                        ])->toArray()
                                    ),
                                Input::make()
                                    ->type('textarea')
                                    ->name('activity_instructions')
                                    ->maxLength(128 + 10)
                                    ->note('Limit is 128 characters + 10 for padding.')
                                    ->label('Activity Instructions (Journey Guide)')
                                    ->translatable(),
                            ]),
                    ]),
            ])
            );
    }
}
