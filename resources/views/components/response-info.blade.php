<x-enlighten-info-panel>
    <x-slot name="title">{{ __('enlighten::messages.response') }}</x-slot>
    <div class="p-4 space-x-4">
        <span class="p-1 bg-{{ $color }}-200 text-{{ $color }}-700">{{ $status }}</span>
        <span class="text-gray-100">{{ $request->response_type }}</span>
    </div>

    @if($showHeaders)
        <x-enlighten-key-value :items="$request->response_headers" title="Response Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
