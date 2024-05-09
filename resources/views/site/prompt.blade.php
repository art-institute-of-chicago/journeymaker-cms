<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Prompt Preview</title>
        <link href="/assets/twill/css/custom.css" rel="stylesheet" />
    </head>
    <body class="custom">
        <div class="p-12">
            <h1 class="mb-4 text-4xl text-gray-900">{{ $item->title }}</h1>
            <h2 class="mb-4 text-2xl text-gray-900">{{ $item->subtitle }}</h2>
            <ul role="list" class="flex flex-col gap-4 mt-10">
                @foreach($item->artworks as $artwork)
                    <li class="px-8 py-10 bg-gray-100 rounded-lg">
                        <div class="flex gap-4">
                            <img class="w-16 h-16 rounded" src="{{ $artwork->artwork->image('override', 'default') }}" alt="">
                            <div>
                                <h3 class="mb-2 text-base font-semibold tracking-tight">{{ $artwork->artwork->title }}</h3>
                                @foreach(collect([
                                    'Detail Narrative (Interface)' => $artwork->detail_narrative,
                                    'Look Again (Journey Guide)' => $artwork->viewing_description,
                                    'Activity Template (Journey Guide)' => App\Models\ActivityTemplate::find($artwork->activity_template)?->label,
                                    'Activity Instructions (Journey Guide)' => $artwork->activity_instructions,
                                ]) as $label => $value)
                                    <p class="mb-1 text-sm leading-6 text-gray-800"><span class="font-bold">{{ $label }}:</span> {{ $value }}</p>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </body>
</html>
