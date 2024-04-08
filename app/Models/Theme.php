<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model implements Sortable
{
    use HasFactory, HasMedias, HasPosition, HasRevisions, HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'title',
        'intro',
    ];

    public $translatedAttributes = [
        'title',
        'intro',
    ];

    public $mediasParams = [
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

    public function prompts()
    {
        return $this->hasMany(ThemePrompt::class);
    }
}
