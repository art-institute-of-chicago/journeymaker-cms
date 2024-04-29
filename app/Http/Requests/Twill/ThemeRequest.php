<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Closure;

class ThemeRequest extends Request
{
    public function rulesForCreate()
    {
        return [
            'title.*' => 'max:23',
        ];
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
        ], [
            'title' => 'max:23',
            'intro' => 'max:255',
        ]);
    }

    public function messages()
    {
        return [
            'title.*.max' => 'Title can be a maximum of 23 characters',
            'intro.*.max' => 'Intro can be a maximum 255 characters',
        ];
    }
}
