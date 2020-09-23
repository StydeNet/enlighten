<div class="mt-8 mb-2">
    <x-enlighten-sub-title>Response</x-enlighten-sub-title>
    <p class="my-2 py-4 border-b border-gray-500 mb-4">
        <span class="p-1 bg-green-200 text-green-700">{{ $codeExample->response_status }}</span>
        <span class="text-gray-100">{{ $codeExample->response_type }}</span>
    </p>
</div>
<x-enlighten-key-value :items="$codeExample->response_headers" title="Response Headers"></x-enlighten-key-value>
