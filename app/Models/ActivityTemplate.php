<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTemplate extends Model
{
    use \Sushi\Sushi;

    protected $rows = [
        [
            'id' => '622',
            'label' => 'Dialogue',
            'img' => null,
        ],
        [
            'id' => '1',
            'label' => 'Pose',
            'img' => null,
        ],
        [
            'id' => '608',
            'label' => 'Sequence',
            'img' => null,
        ],
        [
            'id' => '610',
            'label' => 'Verbal Response',
            'img' => null,
        ],
        [
            'id' => '609',
            'label' => 'Writing and Drawing',
            'img' => null,
        ],
    ];
}
