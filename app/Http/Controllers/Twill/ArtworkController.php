<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Libraries\Api\Builders\Connection\AicConnection;
use App\Libraries\Api\Models\Behaviors\HasApiCalls;
use App\Models\Api\Artwork as ApiArtwork;
use App\Support\Forms\Fields\QueryArtwork;
use Exception;
use Facades\App\Libraries\DamsImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtworkController extends ModuleController
{
    use HasApiCalls;

    protected $moduleName = 'artworks';

    protected function setUpController(): void
    {
        // $this->disableBulkDelete();
        // $this->disableBulkEdit();
        // $this->disableBulkPublish();
        // $this->disableCreate();
        // $this->disableDelete();
        // $this->disableEdit();
        // $this->disablePermalink();
        // $this->disablePublish();
        // $this->disableRestore();

        // $this->enableAugmentedModel();
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
        $model->refreshApi();

        $content = Form::make()
            ->merge($this->additionalFormFields($model, $model->getApiModel()));

        return parent::getForm($model)
            ->addFieldset(
                Fieldset::make()
                    ->title('Content')
                    ->id('content')
                    ->fields($content->toArray())
            );
    }

    // protected function additionalIndexTableColumns(): TableColumns
    // {
    //     $table = parent::additionalIndexTableColumns();

    //     $table->add(
    //         Text::make()->field('description')->title('Description')
    //     );

    //     return $table;
    // }

    protected function additionalFormFields($object, $apiCollectionObject): Form
    {
        $apiValues = array_map(
            fn ($value) => $value ?? (string) $value,
            $apiCollectionObject->getAttributes()
        );

        $latitude = $object->latitude ?? $apiCollectionObject->latitude ?? '';
        $longitude = $object->longitude ?? $apiCollectionObject->longitude ?? '';

        return Form::make()
            ->add(
                BladePartial::make()
                    ->view('forms.image')
                    ->withAdditionalParams(['src' => $apiCollectionObject->imageFront('iiif')['src'] ?? ''])
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
                    ->placeholder($apiValues['artist_display'])
            )
            ->add(
                Checkbox::make()
                    ->name('is_on_view')
                    ->default($apiValues['is_on_view'])
            )
            ->add(
                Input::make()
                    ->name('main_reference_number')
                    ->placeholder($apiValues['main_reference_number'])
                    ->disabled()
                    ->note('readonly')
            )
            ->add(
                Input::make()
                    ->name('credit_line')
                    ->placeholder($apiValues['credit_line'])
            )
            ->add(
                Input::make()
                    ->name('copyright_notice')
                    ->placeholder($apiValues['copyright_notice'])
            )
            ->add(
                Input::make()
                    ->name('latitude')
                    ->label('Latitude')
                    ->type('number')
                    ->placeholder($latitude)
            )
            ->add(
                Input::make()
                    ->name('longitude')
                    ->label('Longitude')
                    ->type('number')
                    ->placeholder($longitude)
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
