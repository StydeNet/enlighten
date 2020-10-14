<x-enlighten-info-panel>
    <x-slot name="title">Response</x-slot>
    <div class="p-4 space-x-4">
        <span class="p-1 bg-{{ $color }}-200 text-{{ $color }}-700">{{ $status }}</span>
        <span class="text-gray-100">{{ $http_data->response_type }}</span>
    </div>

    @if($http_data->response_headers)
        <x-enlighten-key-value :items="$http_data->response_headers" title="Response Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
