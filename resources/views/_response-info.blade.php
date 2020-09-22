<div class="mt-8 mb-2">
    <x-sub-title>Response</x-sub-title>
    <p class="my-2 py-4 border-b border-gray-500 mb-4">
        <span class="p-1 bg-green-200 text-green-700">{{ $codeExample->responseStatus }}</span>
        <span class="text-gray-100">{{ $codeExample->response_type }}</span>
    </p>
</div>
<x-key-value :items="$codeExample->responseHeaders" title="Response Headers"></x-key-value>
