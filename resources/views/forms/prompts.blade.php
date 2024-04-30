@push('extra_css')
    <link href="/assets/twill/css/custom.css" rel="stylesheet" />
@endpush

@php
    $currentPromptId = $currentPromptId ?? null;
@endphp

<div class="custom">
    <ul class="py-2">
        @foreach($theme->prompts()->orderBy('position')->get() as $prompt)
            <li class="text-lg">
                <a
                    @class(['block p-2  hover:bg-slate-100', 'font-semibold' => $prompt->id == $currentPromptId])
                    href="{{ route('twill.themes.prompts.show', [$theme->id, $prompt->id]) }}"
                >
                    {{ ($prompt->id == $currentPromptId? '👉' : '') }}
                    {{ $prompt->title }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
