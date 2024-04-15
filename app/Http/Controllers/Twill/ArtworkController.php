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
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Libraries\Api\Builders\Connection\AicConnection;
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
        $this->enableShowImage();
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
                    ->withAdditionalParams(['src' => null])
            )
            ->add(
                Medias::make()
                    ->name('upload')
                    ->label('Override Image')
                    ->note('This will replace the image above')
            )
            ->add(
                Input::make()
                    ->name('artist_display')
            )
            ->add(
                Checkbox::make()
                    ->name('is_on_view')
            )
            ->add(
                Input::make()
                    ->name('main_reference_number')
                    ->disabled()
                    ->note('readonly')
            )
            ->add(
                Input::make()
                    ->name('credit_line')
            )
            ->add(
                Input::make()
                    ->name('copyright_notice')
            )
            ->add(
                Input::make()
                    ->name('latitude')
                    ->label('Latitude')
                    ->type('number')
            )
            ->add(
                Input::make()
                    ->name('longitude')
                    ->label('Longitude')
                    ->type('number')
            );
    }

    public function queryArtwork(Request $request): JsonResponse
    {
        try {
            $connection = new AicConnection();

            $artworks = (new ApiQueryBuilder($connection, $connection->getQueryGrammar()))
                ->rawSearch([
                    'bool' => [
                        'should' => [
                            ['terms' => ['main_reference_number' => [$request->get('search')]]],
                            ['terms' => ['id' => [$request->get('search')]]],
                        ],
                        'minimum_should_match' => 1,
                    ],
                ])
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
