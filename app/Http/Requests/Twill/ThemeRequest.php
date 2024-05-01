<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Closure;

class ThemeRequest extends Request
{
    public function rulesForCreate()
    {
        return [];
    }

    public function rulesForUpdate()
    {
        return $this->rulesForTranslatedFields([
            'medias.icon' => function (string $attribute, mixed $value, Closure $fail) {
                $icon = $value[0] ?? null;

                if (! $icon) {
                    $fail('The icon is required.');
                }

                if ($icon['width'] < 75 || $icon['width'] < 75) {
                    $fail('The icon must be at least 75x75 pixels.');
                }
            },
        ], []);
    }
}
