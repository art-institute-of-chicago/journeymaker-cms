<?php

namespace Database\Seeders\Behaviors;

use A17\Twill\Http\Controllers\Admin\MediaLibraryController;
use A17\Twill\Http\Requests\Admin\MediaRequest;
use A17\Twill\Models\Media;
use A17\Twill\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasTwillSeeding
{
    public function addTranslation(Model $model, array $translation, string $locale): void
    {
        $model->translations()->getRelated()->updateOrCreate(
            [$model->translations()->getForeignKeyName() => $model->id, 'locale' => $locale],
            $translation
        );
    }

    public function addImage(
        Model $model,
        string $url,
        string $role,
        string $crop = 'default',
        string $ratio = 'default'
    ): void {
        $media = $this->addMediaToLibrary($url);

        $model->medias()->attach($media->id, [
            'metadatas' => '{"video": null, "altText": null, "caption": null}',
            'role' => $role,
            'crop' => $crop,
            'ratio' => $ratio,
            'crop_x' => 0,
            'crop_y' => 0,
            'crop_w' => $media->width,
            'crop_h' => $media->height,
        ]);
    }

    public function addMediaToLibrary(string $url): Media
    {
        $imageName = sanitizeFilename(basename($url));
        $imagePath = sys_get_temp_dir().'/'.$imageName;
        $imageFile = file_get_contents($url);

        file_put_contents($imagePath, $imageFile);

        $endpointType = config('twill.media_library.endpoint_type');

        if ($endpointType === 'local') {
            $request = Request::create('', 'POST', [
                'unique_folder_name' => Str::uuid()->toString(),
                'qqfilename' => $imageName,
                'qqtotalfilesize' => strlen($imageFile),
            ], [], [
                'qqfile' => new UploadedFile($imagePath, $imageName, 'image/jpg', null, true),
            ]);
        } else {
            $uuid = Str::uuid()->toString().'/'.$imageName;
            Storage::disk(config('twill.media_library.disk'))->put($uuid, $imageFile);
            [$width, $height] = getimagesize($imagePath);

            $request = MediaRequest::create('', 'POST', [
                'key' => $uuid,
                'name' => $imageName,
                'width' => $width,
                'height' => $height,
            ]);
        }

        $mediaLibraryController = app()->make(MediaLibraryController::class);

        return $endpointType === 'local' ? $mediaLibraryController->storeFile($request) : $mediaLibraryController->storeReference($request);
    }
}
