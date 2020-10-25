@props(['example'])

@if($example->orphan_queries->isNotEmpty())
    <h2 class="text-gray-300 mb-6 mt-2 text-xl">Setup Queries</h2>
    <div class="grid grid-cols-1 gap-4 mb-8">
        <div class="flex flex-col space-y-4">
            @foreach($example->orphan_queries as $query)
                <x-enlighten-info-panel>
                    <x-slot name="title">Time: {{ $query->time }}</x-slot>
                    <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
                    @if($query->bindings)
                        <x-enlighten-key-value :items="$query->bindings" title="Bindings"></x-enlighten-key-value>
                    @endif
                </x-enlighten-info-panel>
            @endforeach
        </div>
    </div>
@endif

@foreach($example->requests as $http_data)
    <h2 class="text-gray-300 mb-6 mt-2 text-xl">Request #{{ $loop->iteration }} Queries</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @if(!empty($http_data->queries))
            <div class="flex flex-col md:col-span-2 space-y-4">
                @foreach($http_data->queries as $query)
                    <x-enlighten-info-panel>
                        <x-slot name="title">Time: {{ $query->time }}</x-slot>
                        <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
                        @if($query->bindings)
                            <x-enlighten-key-value :items="$query->bindings" title="Bindings"></x-enlighten-key-value>
                        @endif
                    </x-enlighten-info-panel>
                @endforeach
            </div>
        @endif
        <div class="relative h-full">
            <div class="sticky top-0">
                <x-enlighten-request-info :http-data="$http_data" />
            </div>
        </div>
    </div>
@endforeach
