<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Support\Forms\Fields\QueryArtwork;
use Exception;
use Facades\App\Libraries\DamsImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtworkController extends ModuleController
{
    protected $moduleName = 'artworks';

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
        return Form::make([
            QueryArtwork::make()->name('artwork')
                ->updateFormField(
                    formField: 'title',
                    artworkField: 'title',
                    locale: 'en'
                )
                ->updateFormField(
                    formField: 'datahub_id',
                    artworkField: 'id'
                ),
            Input::make()->name('title')->translatable(),
            Input::make()->name('datahub_id')->readOnly(),
        ]);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $content = Form::make()
            ->merge($this->additionalFormFields($model));

        return parent::getForm($model)
            ->addFieldset(
                Fieldset::make()
                    ->title('Content')
                    ->id('content')
                    ->fields($content->toArray())
            );
    }

    protected function additionalFormFields($object): Form
    {
        return Form::make()
            ->add(
                BladePartial::make()
                    ->view('forms.image')
                    ->withAdditionalParams([
                        'src' => DamsImageService::getUrl($object->image_id, $object->mediasParams['iiif']['default'][0]),
                    ])
            )
            ->add(
                Medias::make()
                    ->name('override')
                    ->label('Override Image')
                    ->note('This will replace the image above')
            )
            ->add(
                Input::make()
                    ->name('artist_display')
            )
            ->add(
                Input::make()
                    ->type('textarea')
                    ->name('location_directions')
                    ->label('Location Directions (Journey Guide)')
                    ->translatable()
            )
            ->add(
                Checkbox::make()
                    ->name('is_on_view')
                    ->disabled()
                    ->note('readonly')
            );
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $table = parent::getIndexTableColumns();

        $table->splice(1, 0, [
            Text::make()
                ->field('Image')
                ->customRender(fn ($artwork) => view(
                    'admin.artwork-image',
                    [
                        'src' => $artwork->image('override', 'thumbnail'),
                        'link' => $this->getModuleRoute($artwork, 'edit'),
                    ]
                )->render()),
        ]);

        return $table;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(Text::make()
                ->field('artist_display'))
            ->add(Boolean::make()
                ->field('is_on_view'))
            ->add(Text::make()
                ->field('Themes')
                ->customRender(fn ($artwork) => $artwork->themePrompts()->with('theme')->get()->pluck('theme')
                    ->map(fn ($theme) => '<a href="/admin/themes/'.$theme->id.'/edit">'.$theme->title.'</a>')
                    ->join(', ')));
    }

    public function queryArtwork(Request $request, ApiQueryBuilder $api): JsonResponse
    {
        try {
            $artworks = $api->rawSearch([
                'bool' => [
                    'should' => [
                        ['terms' => ['main_reference_number' => [$request->get('search')]]],
                        ['terms' => ['id' => [$request->get('search')]]],
                    ],
                    'minimum_should_match' => 1,
                ]])
                ->get(['id', 'title', 'artist_display', 'image_id'], '/api/v1/artworks/search')
                ->map(function ($artwork) {
                    $artwork->thumbnail = $artwork->image_id
                        ? DamsImageService::getUrl($artwork->image_id, [
                            'name' => 'thumbnail',
                            'height' => 112,
                            'width' => 112,
                        ])
                        : null;

                    return $artwork;
                });

            return response()->json($artworks);
        } catch (Exception) {
            return response()->json([], 404);
        }
    }
}
