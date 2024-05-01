<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemePromptArtwork extends Model implements Sortable
{
    use HasFactory, HasPosition, HasRevisions, HasTranslation;

    protected $fillable = [
        'theme_prompt_id',
        'artwork_id',
        'title',
        'detail_narrative',
        'viewing_description',
        'activity_instructions',
        'activity_template',
        'position',
    ];

    public $translatedAttributes = [
        'detail_narrative',
        'viewing_description',
        'activity_instructions',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy((new static)->getTable() . '.position');
        });
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query
            ->whereDoesntHave('translations', fn (Builder $query) => $query->where('active', false))
            ->whereDoesntHave('artwork.translations', fn (Builder $query) => $query->where('active', false))
            ->whereRelation('artwork', 'is_on_view', true)
            ->whereRelation('artwork', 'published', true);
    }
}
