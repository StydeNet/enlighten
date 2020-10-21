<x-enlighten-main-layout>

    <x-slot name="title">{{ $title }}</x-slot>

    <div class="w-full my-4">
            <div class="w-full divide-y divide-gray-300 bg-white rounded-lg overflow-hidden">
                @foreach ($group->examples as $example)
                    <a href="{{ $example->url }}" class="flex justify-between items-center p-4 hover:bg-gray-100">

                        <div> <!-- left-->
                            <div class="flex space-x-2 items-center">
                                <span aria-label="Running" class="h-4 w-4 bg-{{ $example->status }}-100 rounded-full flex items-center justify-center">
                                    <span class="h-2 w-2 bg-{{ $example->status }}-400 rounded-full"></span>
                                </span>
                                <span class="text-gray-700 font-medium">{{ $example->title }}</span>
                            </div>
                            <div class="flex space-x-4 mt-4">
                                @if($example->exception)
                                    <span class="flex space-x-2 items-center text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                @endif
                                @if($example->queries)
                                    <span class="flex space-x-1 items-center text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                        <span>{{ $example->queries->count() }}</span>
                                    </span>
                                @endif
                                @if($example->http_data)
                                    <span class="flex space-x-2 items-center text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3h5m0 0v5m0-5l-6 6M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"></path></svg>
                                        <span>{{ $example->http_data->count() }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col space-y-2"> <!-- right-->
                            @foreach($example->http_data as $http_data)
                                <div class="space-y-2">
                                    <div class="flex justify-end space-x-2">
                                        <span class="text-xs px-2 flex items-center rounded-full text-gray-700 bg-gray-300">
                                                {{ $http_data->request_method }}
                                        </span>
                                        <span class="block text-gray-800 text-sm">
                                            /{{ $http_data->request_path }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-center flex-shrink pl-8">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                @endforeach
            </div>
    </div>
</x-enlighten-main-layout>
