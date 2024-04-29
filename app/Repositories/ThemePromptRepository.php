<?php

namespace App\Repositories;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\ThemePrompt;

class ThemePromptRepository extends ModuleRepository
{
    use HandleRevisions, HandleTranslations;

    public function __construct(ThemePrompt $model)
    {
        $this->model = $model;
    }

    public function afterSave(TwillModelContract $model, array $fields): void
    {
        $fields['repeaters']['artwork'] = collect($fields['repeaters']['artwork'] ?? [])->map(fn ($artwork) => [
            'artwork_id' => $artwork['browsers']['artwork'][0]['id'] ?? null,
            ...$artwork,
        ])->toArray();

        $this->updateRepeater($model, $fields, 'artworks', 'ThemePromptArtwork', 'artwork');
        parent::afterSave($model, $fields);
    }

    public function getFormFields(TwillModelContract $model): array
    {
        $fields = parent::getFormFields($model);

        $fields = $this->getFormFieldsForRepeater($model, $fields, 'artworks', 'ThemePromptArtwork', 'artwork');

        $model->load('artworks.artwork');

        $fields['repeaterBrowsers']['artwork'] = $model->artworks->mapWithKeys(fn ($artwork) => [
            'blocks[artworks-'.$artwork->id.'][artwork]' => [
                [
                    'thumbnail' => $artwork->artwork->image('override'),
                    'id' => $artwork->artwork->id,
                    'name' => $artwork->artwork->title,
                ],
            ],
        ])->toArray();

        return $fields;
    }
}
