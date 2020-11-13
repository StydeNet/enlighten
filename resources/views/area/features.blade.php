<x-enlighten-main-layout>
    <x-slot name="title">{{ $area->name }}</x-slot>

    @foreach($groups as $group)
        @if($group->description)
            <p class="text-gray-100 mb-4 bg-gray-800 p-4 rounded-md">{{ $group->description }}</p>
        @endif

        <div class="w-full my-4">
            <div class="w-full divide-y divide-gray-300 bg-white rounded-lg overflow-hidden">
                <div class="w-full p-4 bg-gray-200">
                    <h2 class="text-gray-800 text-lg">{{ $group->title }}</h2>
                </div>
                @foreach ($group->examples as $example)
                    <a href="{{ $example->url }}" class="flex flex-col space-y-4 lg:space-y-0 lg:flex-row justify-between p-4 hover:bg-gray-100">

                        <div class="w-full lg:flex-1">
                            <div class="flex space-x-2 items-start">
                                <x-enlighten-status-badge :model="$example" size="6"></x-enlighten-status-badge>
                                <span class="text-gray-700 font-medium">{{ $example->title }}</span>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 flex justify-between">
                            <div class="flex flex-col space-y-2">
                                @foreach($example->requests as $request)
                                    <div class="space-y-2">
                                        <div class="flex  space-x-2">
                                            <span class="text-sm px-2 flex items-center text-gray-700 bg-gray-200">
                                                {{ $request->request_method }}: {{ $request->route ?: $request->request_path }}
                                            </span>
                                            <span class="text-sm px-2 flex items-center text-{{ $request->getStatus() }}-700 bg-{{ $request->getStatus() }}-200">
                                                {{ $request->response_status }} {{ $request->response_type }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex space-x-4 w-2/12 items-start">
                                @if($example->has_exception)
                                    <span class="flex space-x-2 items-center text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                @endif
                                @if ($showQueries)
                                    @if($example->queries_count > 0)
                                        <span class="flex space-x-1 items-center text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                            <span>{{ $example->queries_count }}</span>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</x-enlighten-main-layout>
