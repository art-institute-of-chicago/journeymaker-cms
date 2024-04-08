<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
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

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
