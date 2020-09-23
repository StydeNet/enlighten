<div class="my-2">
    <x-enlighten-sub-title>Request</x-enlighten-sub-title>
    <p class="py-4 border-b border-gray-500 mb-4">
        <span class="p-1 bg-blue-200 text-blue-500">{{ $codeExample->request_method }}</span>
        <span class="text-gray-200">{{ $codeExample->route }}</span>
    </p>

    @includeWhen($codeExample->route_parameters, 'enlighten::block.parameters-table')
    @includeWhen($codeExample->request_input, 'enlighten::block.request-input-table')

    <x-enlighten-key-value :items="$codeExample->request_headers" title="Request Headers"></x-enlighten-key-value>
</div>
