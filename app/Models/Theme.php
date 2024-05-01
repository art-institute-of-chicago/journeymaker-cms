<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model implements Sortable
{
    use HasFactory, HasMedias, HasPosition, HasRevisions, HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'title',
        'intro',
        'journey_guide',
    ];

    public $translatedAttributes = [
        'title',
        'intro',
        'journey_guide',
    ];

    public $mediasParams = [
        'shape_face' => [
            'default' => [
                [
                    'name' => 'default',
                    'minValues' => [
                        'width' => 2888,
                        'height' => 2789,
                    ],
                ],
            ],
        ],
        'icon' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 1,
                    'minValues' => [
                        'width' => 75,
                        'height' => 75,
                    ],
                ],
            ],
        ],
        'cover' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 0,
                    'minValues' => [
                        'width' => 1125,
                        'height' => 1500,
                    ],
                ],
            ],
        ],
        'cover_home' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 0,
                    'minValues' => [
                        'width' => 1125,
                        'height' => 1500,
                    ],
                ],
            ],
        ],
        'backgrounds' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                    'minValues' => [
                        'width' => 1920,
                        'height' => 1080,
                    ],
                ],
            ],
        ],
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('position');
        });
    }

    public function prompts()
    {
        return $this->hasMany(ThemePrompt::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query
            ->published()
            ->whereDoesntHave('translations', fn (Builder $query) => $query->where('active', false));
    }
}
