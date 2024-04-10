<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Libraries\Api\Models\Behaviors\HasApiCalls;
use App\Models\Api\Artwork as ApiArtwork;
use App\Support\Forms\Fields\QueryArtwork;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtworkController extends BaseModuleController
{
    use HasApiCalls;

    protected $moduleName = 'artworks';

    protected function setUpController(): void
    {
        $this->disablePermalink();
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
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('description')->label('Description')->translatable()
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('description')->title('Description')
        );

        return $table;
    }

    public function queryArtwork(Request $request): JsonResponse
    {
        try {
            $artworks = ApiArtwork::query()->byId($request->get('search'))
                ->get(['id', 'title', 'artist_display', 'image_id'])
                ->map(fn ($artwork) => $artwork->loadThumbnail());

            return response()->json($artworks);
        } catch (Exception $th) {
            return response()->json([], 404);
        }
    }
}
