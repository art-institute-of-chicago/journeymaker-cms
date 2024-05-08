<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Artwork Preview</title>
        <link href="/assets/twill/css/custom.css" rel="stylesheet" />
    </head>
    <body class="custom">
        <div class="p-12">
            <h1 class="mb-4 text-4xl text-gray-900">{{ $item->title }}</h1>
            <img class="mb-4 max-w-96" src="{{ $item->image('override', 'default') }}">
            <p class="mb-4 text-gray-700"><span class="font-bold ">Artist:</span> {{ $item->artist }}</p>
            <p class="mb-4 text-gray-700"><span class="font-bold ">Location Directions:</span> {{ $item->location_directions }}</p>
        </div>
    </body>
</html>
