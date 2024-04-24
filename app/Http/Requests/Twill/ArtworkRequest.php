<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class ArtworkRequest extends Request
{
    public function rulesForCreate()
    {
        return [
            'datahub_id' => 'required|unique:artworks,datahub_id',
        ];
    }

    public function rulesForUpdate()
    {
        return [];
    }
}
