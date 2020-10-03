<x-enlighten-info-panel>
    <x-slot name="title">Response</x-slot>
    <div class="p-4 space-x-4">
        <span class="p-1 bg-green-200 text-green-700">
            {{ $codeExample->http_data->response_status }}
        </span>
        <span class="text-gray-100">{{ $codeExample->http_data->response_type }}</span>
    </div>

    @if($codeExample->http_data->response_headers)
        <x-enlighten-key-value :items="$codeExample->http_data->response_headers" title="Response Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
