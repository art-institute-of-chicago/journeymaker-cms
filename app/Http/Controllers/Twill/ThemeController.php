<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Fieldsets;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Image;
use A17\Twill\Services\Listings\TableColumns;

class ThemeController extends BaseModuleController
{
    protected $moduleName = 'themes';

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->disableBulkEdit();
        $this->disableBulkPublish();
        $this->disableBulkRestore();
        $this->disableBulkForceDelete();
    }

    public function getCreateForm(): Form
    {
        return Form::make()->add(
            Input::make()
                ->name('title')
                ->maxLength(23)
                ->translatable()
        );
    }

    public function getForm(TwillModelContract $model): Form
    {
        return parent::getForm($model)
            ->withFieldSets(new Fieldsets([
                Fieldset::make()->title('Content')->id('content')->fields([
                    Input::make()
                        ->name('intro')
                        ->label('Intro')
                        ->maxLength(225)
                        ->translatable(),

                    Input::make()
                        ->name('journey_guide')
                        ->label('Journey Guide')
                        ->translatable(),
                ]),
                Fieldset::make()->title('Media')->id('media')->fields([
                    Medias::make()
                        ->name('shape_face')
                        ->label('Shape Face')
                        ->fieldNote('Animated Theme icon as it will appear on the Theme selector shape.'),

                    Medias::make()
                        ->name('icon')
                        ->label('Icon')
                        ->fieldNote('Simplified icon appears on subsequent pages as users build their Journey Guide.'),

                    Medias::make()
                        ->name('cover')
                        ->label('Guide Cover Art')
                        ->fieldNote('Appears on the Journey Guide to correspond with the selected Theme.'),

                    Medias::make()
                        ->name('cover_home')
                        ->label('Guide Cover Art (Home Companion)')
                        ->fieldNote('Appears on the printed Journey Guide.'),

                    Medias::make()
                        ->name('bgs')
                        ->label('Backgrounds')
                        ->fieldNote('Appears behind the Theme on the Theme selector page.')
                        ->max(10),
                ]),
            ])
            );
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $table = parent::getIndexTableColumns();

        $table->splice(1, 0, [
            Image::make()->field('icon'),
        ]);

        return $table;
    }
}
