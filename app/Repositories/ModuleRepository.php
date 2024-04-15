<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository as BaseModuleRepository;
use Illuminate\Support\Str;

abstract class ModuleRepository extends BaseModuleRepository
{
    /**
     * Remove trailing newlines from WYSIWYG fields
     */
    public function prepareFieldsBeforeSave($object, $fields): array
    {
        // Fields
        foreach ($fields as $key => $field) {
            $fields[$key] = Str::rightTrim($field, '<p><br></p>');

            $fields[$key] = (string) Str::of($field)
                ->whenEndsWith('<p><br></p>', fn ($string) => $string->beforeLast('<p><br></p>'));
        }

        // Block content (for `HasBlocks` only)
        if (isset($fields['blocks'])) {
            foreach ($fields['blocks'] as $blockKey => $block) {
                foreach ($block['content'] as $contentKey => $content) {
                    $fields['blocks'][$blockKey]['content'][$contentKey] = (string) Str::of($content)
                        ->whenEndsWith('<p><br></p>', fn ($string) => $string->beforeLast('<p><br></p>'));
                }
            }
        }

        return parent::prepareFieldsBeforeSave($object, $fields);
    }
}
