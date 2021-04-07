@props(['example'])

@php
    $queryGroups = $example->queries->groupBy('request_id');
@endphp

@foreach($queryGroups as $group)
    <div x-data="{
            open: {{ $group->first()->context === 'test' ? 0 : 1 }}
        }">
        <label x-on:click="open = !open"  class="flex items-center space-x-2 cursor-pointer text-gray-300 hover:text-gray-100 mb-6 mt-2 text-xl">
            <span>
                @if ($group->first()->context === 'request')
                    {{ __('enlighten::messages.request_queries') }}
                @elseif (($group->first()->context === 'snippet'))
                    {{ __('enlighten::messages.snippet_queries') }}
                @else
                    {{ __('enlighten::messages.test_queries') }}
                @endif
            </span>
            <svg x-cloak x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            <svg x-cloak x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </label>
        @if($group->first()->context === 'request')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" x-cloak x-show="open">
                <div class="flex flex-col md:col-span-2 space-y-4">
                    @foreach($group as $query)
                        <x-enlighten-info-panel>
                            <x-slot name="title">{{ __('enlighten::messages.time') }}: {{ $query->time }}</x-slot>
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
        @else
            <div class="grid grid-cols-1 gap-4 mb-8" x-cloak x-show="open">
                <div class="flex flex-col space-y-4">
                    @foreach($group as $query)
                        <x-enlighten-info-panel>
                            <x-slot name="title">{{ __('enlighten::messages.time') }}: {{ $query->time }}</x-slot>
                            <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
                            @if($query->bindings)
                                <x-enlighten-key-value :items="$query->bindings" title="Bindings"></x-enlighten-key-value>
                            @endif
                        </x-enlighten-info-panel>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endforeach
