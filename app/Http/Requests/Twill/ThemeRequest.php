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
                $image = $value[0] ?? null;

                if ($image['width'] < 75 || $image['height'] < 75) {
                    $fail('The icon must be at least 75x75 pixels.');
                }
            },
            'medias.cover' => function (string $attribute, mixed $value, Closure $fail) {
                $image = $value[0] ?? null;

                if ($image['width'] !== 1125 || $image['height'] !== 1500) {
                    $fail('The guide cover art must be 1125x1500 pixels.');
                }
            },
            'medias.cover_home' => function (string $attribute, mixed $value, Closure $fail) {
                $image = $value[0] ?? null;

                if ($image['width'] !== 1125 || $image['height'] !== 1500) {
                    $fail('The guide cover art (home companion) must be 1125x1500 pixels.');
                }
            },
            'medias.backgrounds' => function (string $attribute, mixed $value, Closure $fail) {
                collect($value)->each(function ($image) use ($fail) {
                    if ($image['width'] < 1920 || $image['height'] < 1080) {
                        $fail('All background images must be at least 1920x1080 pixels.');
                    }
                });
            },
        ], []);
    }
}
