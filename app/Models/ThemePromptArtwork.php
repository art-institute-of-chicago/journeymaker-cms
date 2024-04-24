<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemePromptArtwork extends Model implements Sortable
{
    use HasFactory, HasPosition, HasRevisions, HasTranslation;

    protected $fillable = [
        'published',
        'theme_prompt_id',
        'artwork_id',
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

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }
}
