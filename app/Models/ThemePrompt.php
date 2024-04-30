<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemePrompt extends Model implements Sortable
{
    use HasFactory, HasPosition, HasRevisions, HasTranslation;

    protected $fillable = [
        'published',
        'title',
        'subtitle',
        'position',
        'theme_id',
    ];

    public $translatedAttributes = [
        'title',
        'subtitle',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('position');
        });
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function artworks()
    {
        return $this->hasMany(ThemePromptArtwork::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query
            ->published()
            ->whereDoesntHave('translations', fn (Builder $query) => $query->where('active', false));
    }
}
