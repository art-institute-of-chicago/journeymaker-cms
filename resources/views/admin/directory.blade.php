@extends('twill::layouts.free')

@pushOnce('extra_css')
    <link href="/assets/twill/css/custom.css" rel="stylesheet" />
@endPushOnce

@section('customPageContent')
    <div class="custom">
        @foreach($themes as $theme)
            <div class="mb-8">
                <a class="no-underline" href="{{ route('twill.themes.edit', $theme->id) }}">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <img class="w-12 h-12 rounded-full" src="{{ $theme->image('icon') }}" alt="">
                        </div>
                        <h1 class="text-base font-semibold leading-6 text-gray-900">{{ $theme->title }}</h1>
                    </div>
                </a>

                <div class="flow-root mt-4">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full">
                                <tbody class="bg-white">
                                    @foreach($theme->prompts as $prompt)
                                        <tr class="">
                                            <th colspan="3" scope="colgroup" class="py-4 pl-4 pr-3 text-sm font-semibold text-left text-gray-900 bg-gray-50 sm:pl-3">
                                                <a class="no-underline" href="{{ route('twill.themes.prompts.edit', [$theme->id, $prompt->id]) }}">
                                                    {{ $prompt->title }}
                                                </a>
                                            </th>
                                        </tr>
                                        @foreach($prompt->artworks as $artwork)
                                            <tr>
                                                <td class="px-3 py-4 align-middle">
                                                    <img class="w-8 h-8 rounded-lg" src="{{ $artwork->artwork->image('thumbnail') }}" alt="">
                                                </td>
                                                <td class="px-3 py-4 text-sm text-gray-600 align-middle whitespace-nowrap">
                                                    <a class="no-underline" href="{{ route('twill.artworks.edit', $artwork->artwork->id) }}">
                                                        {{ $artwork->title }}
                                                        <br><small class="text-xs text-gray-400">{{ $artwork->artwork->artist }}</small>
                                                    </a>
                                                </td>
                                                <td class="px-3 py-4 text-sm" title="{{ $artwork->artwork->is_on_view? 'On View' : 'Off View' }}">
                                                    {{ $artwork->artwork->is_on_view? '✅' : '❌' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
