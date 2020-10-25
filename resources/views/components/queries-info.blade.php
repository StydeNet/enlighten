@props(['example'])

@php
    $queryGroups = $example->queries->chunkWhile(function ($query, $key, $chunk) {
        return $query->request_id === $chunk->last()->request_id;
    });
@endphp

@foreach($queryGroups as $group)
    @if($group->first()->context === 'test')
        <h2 class="text-gray-300 mb-6 mt-2 text-xl">Test Queries</h2>
        <div class="grid grid-cols-1 gap-4 mb-8">
            <div class="flex flex-col space-y-4">
                @foreach($group as $query)
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
    @else
        <h2 class="text-gray-300 mb-6 mt-2 text-xl">Request Queries</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="flex flex-col md:col-span-2 space-y-4">
                @foreach($group as $query)
                    <x-enlighten-info-panel>
                        <x-slot name="title">Time: {{ $query->time }}</x-slot>
                        <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
                        @if($query->bindings)
                            <x-enlighten-key-value :items="$query->bindings" title="Bindings"></x-enlighten-key-value>
                        @endif
                    </x-enlighten-info-panel>
                @endforeach
            </div>
            <div class="relative h-full">
                <div class="sticky top-0">
                    <x-enlighten-request-info :request="$group->first()->request" />
                </div>
            </div>
        </div>
    @endif
@endforeach
