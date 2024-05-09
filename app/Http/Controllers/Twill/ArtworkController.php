<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\BelongsToFilter;
use A17\Twill\Services\Listings\Filters\BooleanFilter;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use A17\Twill\Services\Listings\Filters\QuickFilters;
use A17\Twill\Services\Listings\Filters\TableFilters;
use A17\Twill\Services\Listings\TableColumns;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Models\Artwork;
use App\Models\Theme;
use App\Support\Forms\Fields\QueryArtwork;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $this->setSearchColumns(['title', 'artist']);
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
            Input::make()->name('datahub_id')->label('Object ID')->readOnly(),
        ]);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $apiArtwork = $model->getArtworkApiData();
        $apiGallery = $model->getGalleryApiData($apiArtwork->gallery_id);

        return parent::getForm($model)
            ->addFieldset(
                Fieldset::make()
                    ->title('Content')
                    ->id('content')
                    ->fields([
                        Input::make()
                            ->name('title')
                            ->maxLength(255)
                            ->translatable(),
                        BladePartial::make()
                            ->view('forms.image')
                            ->withAdditionalParams([
                                'src' => $model->getDimImageUrl($model->mediasParams['iiif']['default'][0]),
                            ]),
                        Medias::make()
                            ->name('override')
                            ->label('Override Image')
                            ->note('This will replace the image above'),
                        Input::make()
                            ->name('artist')
                            ->translatable(),
                        Input::make()
                            ->type('textarea')
                            ->name('location_directions')
                            ->maxLength(145 + 10)
                            ->note('Limit is 145 characters + 10 for padding.')
                            ->label('Location Directions (Journey Guide)')
                            ->translatable(),
                        BladePartial::make()
                            ->view('forms.object-info')
                            ->withAdditionalParams([
                                'isOnView' => $model->is_on_view ?? false,
                                'datahubId' => $model->datahub_id ?? '',
                                'mainReferenceNumber' => $apiArtwork->main_reference_number ?? '',
                                'gallery' => $apiGallery->title ?? '',
                                'floor' => $apiGallery->floor ?? '',
                            ]),
                    ])
            );
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $table = parent::getIndexTableColumns();

        // Sort title asc by default
        $table[1]->sortByDefault();

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
                ->field('artist')
                ->sortable()
            )
            ->add(Boolean::make()
                ->field('is_on_view')
                ->sortable()
            )
            ->add(Text::make()
                ->field('gallery_name')
                ->customRender(fn ($artwork) => $artwork->gallery_name . '' ?? ''))
            ->add(Text::make()
                ->field('Themes')
                ->customRender(fn ($artwork) => $artwork->themePrompts()->with('theme')->get()->pluck('theme')
                    ->map(fn ($theme) => '<a href="/admin/themes/'.$theme->id.'/edit">'.$theme->title.'</a>')
                    ->join(', ')));
    }

    public function quickFilters(): QuickFilters
    {
        return QuickFilters::make([
            QuickFilter::make()
                ->label(twillTrans('twill::lang.listing.filter.all-items'))
                ->queryString('all')
                ->amount(fn () => $this->repository->getCountByStatusSlug('all')),
            QuickFilter::make()
                ->label('Visible')
                ->queryString('visible')
                ->scope('active')
                ->amount(fn () => $this->repository->getCountVisible()),
            QuickFilter::make()
                ->label('Hidden')
                ->queryString('hidden')
                ->scope('notActive')
                ->amount(fn () => $this->repository->getCountHidden()),
        ]);
    }

    public function filters(): TableFilters
    {
        return TableFilters::make([
            BelongsToFilter::make()->field('themePrompts.theme')->label('Theme')->model(Theme::class),
            BooleanFilter::make()->field('is_on_view')->label('On View'),
            BooleanFilter::make()->field('published')->label('Published'),
        ]);
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
                ->get(['id', 'main_reference_number', 'is_on_view', 'title', 'artist_title', 'artist_display', 'image_id'], '/api/v1/artworks/search')
                ->map(function ($artwork) {
                    $artwork->artist = Str::of($artwork->artist_title ?: $artwork->artist_display)
                        ->before("\n")->trim()->__toString();

                    $artwork->thumbnail = Artwork::make((array) $artwork)->getDimImageUrl([
                        'name' => 'thumbnail',
                        'height' => 112,
                        'width' => 112,
                    ]);

                    return $artwork;
                });

            return response()->json($artworks);
        } catch (Exception) {
            return response()->json([], 404);
        }
    }
}
