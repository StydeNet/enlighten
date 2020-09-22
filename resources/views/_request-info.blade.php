<div class="my-2">
    <x-sub-title>Request</x-sub-title>
    <p class="py-4 border-b border-gray-500 mb-4">
        <span class="p-1 bg-blue-200 text-blue-500">{{ $codeExample->requestMethod }}</span>
        <span class="text-gray-200">{{ $codeExample->route }}</span>
    </p>

{{--    @includeWhen($codeExample->route_parameters, '_parameters-table')--}}

{{--    @includeWhen($codeExample->request_input, '_request-input-table')--}}
{{--    <x-key-value :items="$codeExample->requestHeaders" title="Request Headers"></x-key-value>--}}
</div>
