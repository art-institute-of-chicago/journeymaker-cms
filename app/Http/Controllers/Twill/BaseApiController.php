<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Facades\TwillPermissions;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\FeaturedStatus;
use A17\Twill\Services\Listings\Columns\Languages;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Services\Listings\Columns\ScheduledStatus;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\BasicFilter;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Behaviors\HandlesTitleMarkup;
use App\Http\Controllers\Twill\Columns\ApiImage;
use App\Libraries\Api\Filters\Search;
use App\Repositories\Api\BaseApiRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseApiController extends ModuleController
{
    use HandlesTitleMarkup;

    protected $hasAugmentedModel = false;

    protected $localElements = [];

    protected $defaultFilters = [
        'search' => 'search',
    ];

    protected $displayName;

    protected function setUpController(): void
    {
        $this->disableBulkDelete();
        $this->disableBulkEdit();
        $this->disableBulkPublish();
        $this->disableCreate();
        $this->disableDelete();
        $this->disableEdit();
        $this->disablePermalink();
        $this->disablePublish();
        $this->disableRestore();
    }

    /**
     * Remove Twill table filters.
     */
    public function getIndexTableMainFilters($items, $scopes = []): array
    {
        return [];
    }

    /**
     * Create a new model for augmentation, filling in only the `datahub_id`,
     * then redirect to the edit form.
     */
    public function augment(string $datahubId)
    {
        if ($apiModel = $this->getApiRepository()->getById($datahubId)) {
            $augmentedModel = $this->getRepository()->firstOrCreate(['datahub_id' => $apiModel->id]);
        }

        return $this->redirectToForm($augmentedModel->id);
    }

    public function feature()
    {
        if (($id = $this->request->get('id')) && ($apiModel = $this->getApiRepository()->getById($id))) {
            $augmentedModel = $this->getRepository()->firstOrCreate(['datahub_id' => $apiModel->id]);
            $this->request->merge(['id' => $augmentedModel->id]);
        }

        return parent::feature();
    }

    protected function getRepository(): ModuleRepository
    {
        if ($this->hasAugmentedModel) {
            return parent::getRepository();
        }

        return $this->getApiRepository();
    }

    protected function getApiRepository(): BaseApiRepository
    {
        return $this->app->make("{$this->namespace}\Repositories\\Api\\".$this->modelName.'Repository');
    }

    public function getIndexItems(array $scopes = [], bool $forcePagination = false): Collection|LengthAwarePaginator
    {
        if (TwillPermissions::enabled() && TwillPermissions::getPermissionModule($this->moduleName)) {
            $scopes += ['accessible' => true];
        }

        $requestFilters = $this->getRequestFilters();
        $appliedFilters = [];
        $this->applyQuickFilters($requestFilters, $appliedFilters);
        $this->applyBasicFilters($requestFilters, $appliedFilters);

        return $this->transformIndexItems(
            $this->getApiData($scopes, $forcePagination, $appliedFilters)
        );
    }

    public function getApiData($scopes = [], $forcePagination = false, $appliedFilters = [])
    {
        return $this->getApiRepository()->get(
            with: $this->indexWith,
            scopes: $scopes,
            orders: $this->orderScope(),
            perPage: $this->request->get('offset') ?? $this->perPage,
            forcePagination: $forcePagination,
            appliedFilters: $appliedFilters
        );
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = TableColumns::make();

        if ($this->getIndexOption('publish')) {
            $columns->add(
                PublishStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.published'))
                    ->sortable()
                    ->optional()
            );
        }

        if ($this->getIndexOption('showImage')) {
            $columns->add(
                ApiImage::make()
                    ->field('thumbnail')
                    ->title(twillTrans('Image'))
            );
        }

        if ($this->getIndexOption('feature') && $this->repository->isFillable('featured')) {
            $columns->add(
                FeaturedStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.featured'))
            );
        }

        $columns->add(
            Boolean::make()
                ->field('is_augmented')
                ->optional()
                ->hide()
        );
        $columns->add(
            Text::make()
                ->field('id')
                ->title(($this->displayName ?: 'Datahub').' Id')
                ->optional()
        );
        $columns->add(
            Text::make()
                ->field('source_updated_at')
                ->optional()
                ->hide()
        );
        $columns->add(
            Text::make()
                ->field('updated_at')
                ->optional()
                ->hide()
        );
        $columns->add(
            $this->getTitleColumn()
                ->linkCell(function (TwillModelContract $model) {
                    if ($model->is_augmented) {
                        $action = 'edit';
                        $id = $model->getAugmentedModel()->id;
                    } else {
                        $action = 'augment';
                        $id = $model->id;
                    }

                    return moduleRoute($this->moduleName, $this->routePrefix, $action, [$id]);
                })
        );
        $columns = $columns->merge($this->additionalIndexTableColumns());

        if ($this->getIndexOption('includeScheduledInList') && $this->repository->isFillable('publish_start_date')) {
            $columns->add(
                ScheduledStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.published'))
                    ->optional()
            );
        }

        if ($this->moduleHas('translations') && count(getLocales()) > 1) {
            $columns->add(
                Languages::make()
                    ->title(twillTrans('twill::lang.listing.languages'))
                    ->optional()
            );
        }

        return $columns;
    }

    protected function getBrowserTableColumns(): TableColumns
    {
        $columns = TableColumns::make();

        if ($this->moduleHas('medias')) {
            $columns->add(
                ApiImage::make()
                    ->field('thumbnail')
                    ->rounded()
                    ->title(twillTrans('Image'))
            );
        }

        return $columns->merge($this->additionalBrowserTableColumns());
    }

    public function getForm(TwillModelContract $model): Form
    {
        $model->refreshApi();

        $title = $this->getTitleField()->translatable();

        if (classHasTrait($model::class, HasTranslation::class)) {
            $title->translatable();
        }

        $content = Form::make()
            ->add($title)
            ->merge($this->additionalFormFields($model, $model->getApiModel()));

        return parent::getForm($model)
            ->addFieldset(
                Fieldset::make()
                    ->title('Content')
                    ->id('content')
                    ->fields($content->toArray())
            );
    }

    protected function additionalFormFields($model, $apiModel): Form
    {
        return new Form();
    }

    public function getSideFieldSets(TwillModelContract $model): Form
    {
        return parent::getSideFieldSets($model)
            // For some reason, the side form will not render unless there is a
            // field in the default Content fieldset. 🤷
            ->add(
                Input::make()
                    ->name('id')
                    ->disabled()
                    ->note('readonly')
            )
            ->addFieldset(
                Fieldset::make()
                    ->id('datahub')
                    ->title('Datahub')
                    ->closed()
                    ->fields([
                        Input::make()
                            ->name('datahub_id')
                            ->disabled()
                            ->note('readonly'),
                        Input::make()
                            ->name('source_updated_at')
                            ->disabled()
                            ->note('readonly'),
                    ])
            )
            ->addFieldset(
                Fieldset::make()
                    ->id('timestamps')
                    ->title('Timestamps')
                    ->closed()
                    ->fields([
                        Input::make()
                            ->name('created_at')
                            ->disabled()
                            ->note('readonly'),
                        Input::make()
                            ->name('updated_at')
                            ->disabled()
                            ->note('readonly'),
                    ])
            );
    }

    /**
     * Get the applied quick filter.
     */
    protected function applyQuickFilters(array &$requestFilters, array &$appliedFilters): void
    {
        if (array_key_exists('status', $requestFilters)) {
            $filter = $this->quickFilters()->filter(
                fn (QuickFilter $filter) => $filter->getQueryString() === $requestFilters['status']
            )->first();

            if ($filter !== null) {
                $appliedFilters[] = $filter;
            }
        }
        unset($requestFilters['status']);
    }

    /**
     * Get other filters that need to applied. Use the API search filter when
     * requested.
     */
    protected function applyBasicFilters(array &$requestFilters, array &$appliedFilters): void
    {
        foreach ($requestFilters as $filterKey => $filterValue) {
            $filter = $this->filters()->filter(
                fn (BasicFilter $filter) => $filter->getQueryString() === $filterKey
            )->first();

            if ($filter !== null) {
                $appliedFilters[] = $filter->withFilterValue($filterValue);
            } elseif ($filterKey === 'search') {
                $appliedFilters[] = Search::make()
                    ->searchFor($filterValue)
                    ->searchColumns($this->searchColumns);
            }
        }
    }

    protected function transformIndexItems(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator
    {
        if ($this->hasAugmentedModel) {
            $ids = $items->pluck('id')->toArray();
            $this->localElements = $this->repository->whereIn('datahub_id', $ids)->get();
            $items->setCollection($items->getCollection()->map(function ($item) {
                if ($element = collect($this->localElements)->where('datahub_id', $item->id)->first()) {
                    $item->setAugmentedModel($element);
                }

                return $item;
            }));
        }

        return $items;
    }

    /**
     * Disable sorting by default for API listings. This has to be implemented individually on each controller
     */
    protected function orderScope(): array
    {
        return [];
    }

    protected function indexItemData($item)
    {
        if ($this->hasAugmentedModel) {
            if ($localItem = collect($this->localElements)->where('datahub_id', $item->id)->first()) {
                $editRoute = moduleRoute($this->moduleName, $this->routePrefix, 'edit', [$localItem->id]);
            } else {
                $editRoute = moduleRoute($this->moduleName, $this->routePrefix, 'augment', [$item->id]);
            }
        } else {
            $editRoute = null;
        }

        return ['edit' => $editRoute];
    }

    /**
     * Option to setup links and the possibility of augmenting a model
     */
    protected function enableAugmentedModel(): void
    {
        $this->hasAugmentedModel = true;
    }

    protected function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }
}