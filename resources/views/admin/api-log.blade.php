@extends('twill::layouts.free')

@push('extra_css')
    <link href="/assets/twill/css/custom.css" rel="stylesheet" />
@endpush

@section('customPageContent')
    <div class="custom">
        <div class="mb-8">
            <h1 class="text-base font-semibold leading-6 text-gray-900">Changes to API Data</h1>
            <div class="flow-root mt-4">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full">
                            <thead class="bg-white">
                                <tr>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 whitespace-nowrap">Object Id</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 whitespace-nowrap">Field</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 whitespace-nowrap">Old Value</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 whitespace-nowrap">New Value</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 whitespace-nowrap">Updated</th>
                                </tr>
                                </thead>
                            <tbody class="bg-white">
                                @foreach($logs as $log)
                                    <tr>
                                        <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            <a href="{{ route('twill.artworks.edit', $log->artwork->id) }}">
                                                {{ $log->datahub_id }}
                                                <img class="w-8 h-8 rounded-lg" src="{{ $log->artwork->image('thumbnail') }}" alt="">
                                            </a>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ Str::of($log->field)->headline() }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-600">
                                            {{ $log->old_value }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-600">
                                            {{ $log->new_value }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ Carbon\Carbon::parse($log->updated_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
