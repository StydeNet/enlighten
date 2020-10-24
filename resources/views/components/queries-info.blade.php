@props(['example'])

@if($example->orphan_queries->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="flex flex-col space-y-4">
            @foreach($example->orphan_queries as $query)
                <x-enlighten-info-panel>
                    <x-slot name="title">Time: {{ $query->time }} (Setup query)</x-slot>
                    <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
                    @if($query->bindings)
                        <x-enlighten-key-value :items="$query->bindings" title="Bindings"></x-enlighten-key-value>
                    @endif
                </x-enlighten-info-panel>
            @endforeach
        </div>
    </div>
@endif

@foreach($example->http_data as $http_data)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        @if(!empty($http_data->queries))
            <div class="flex flex-col space-y-4">
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
