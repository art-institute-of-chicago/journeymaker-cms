@php
    $images = collect([
        'Icon' => $item->image('icon', 'default'),
        'Cover' => $item->image('cover', 'default'),
        'Cover Home' => $item->image('cover_home', 'default'),
    ])->merge(
        collect($item->images('backgrounds', 'default'))->mapWithKeys(
            fn ($image, $index) => ["Background " . ($index + 1) => $image]
        )
    );
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Theme Preview</title>
        <link href="/assets/twill/css/custom.css" rel="stylesheet" />
    </head>
    <body class="custom">
        <div class="p-12">
            <h1 class="mb-4 text-4xl text-gray-900">{{ $item->title }}</h1>
            <p class="text-gray-700 "><span class="font-bold ">Intro:</span> {{ $item->intro }}</p>
            <p class="mb-4 text-gray-700"><span class="font-bold ">Journey Guide Cover Title:</span> {{ $item->journey_guide }}</p>
            <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                @foreach($images as $label => $src)
                    <li class="relative">
                        <div class="block w-full overflow-hidden bg-gray-100 rounded-lg group aspect-h-7 aspect-w-10 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
                            <img src="{{ $src }}" class="object-contain pointer-events-none group-hover:opacity-75">
                        </div>
                        <p class="block mt-2 text-sm font-medium text-gray-700 truncate pointer-events-none">{{ $label }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </body>
</html>
