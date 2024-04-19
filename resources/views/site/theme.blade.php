<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Theme Preview</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <div class="flex h-full flex-col justify-between p-8">
            <h1 class="text-5xl">Theme Preview</h1>

            <h2 class="text-4xl">{{ $item->title }}</h2>

            <p>{{ $item->intro }}</p>
        </div>
    </body>
</html>
