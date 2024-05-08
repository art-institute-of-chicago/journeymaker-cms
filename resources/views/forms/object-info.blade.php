@pushOnce('extra_css')
    <link href="/assets/twill/css/custom.css" rel="stylesheet" />
@endPushOnce

@php
    $items = [
        [
            'label' => 'Is On View',
            'value' => $isOnView ? '✅' : '❌'
        ],
        [
            'label' => 'Object ID',
            'value' => $datahubId
        ],
        [
            'label' => 'Main Reference Number',
            'value' => $mainReferenceNumber
        ],
        [
            'label' => 'Gallery',
            'value' => $gallery
        ],
        [
            'label' => 'Floor',
            'value' => $floor
        ]
    ];
@endphp

<div class="custom">
    <hr class="mt-10">
    <div class="mt-8">
        <h3 class="font-semibold leading-7 text-gray-900">Object Information</h3>
        <p class="text-sm leading-6 text-gray-500">This information is pulled directly from the API and cannot be edited.</p>
    </div>
    <div class="flex flex-col mt-5">
        @foreach ($items as $item)
            <div class="flex gap-2 py-1 my-1">
                <div class="font-medium leading-6 text-gray-900 ">{{ $item['label'] }}:</div>
                <div class="leading-6 text-gray-700">{{ $item['value'] }}</div>
            </div>
        @endforeach
    </div>
</div>
