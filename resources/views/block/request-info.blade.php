<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value
        :items="[
            'Method' => $codeExample->request_method,
            'URL' => $codeExample->request_path . ($codeExample->request_query_parameters ? '?' . http_build_query($codeExample->request_query_parameters) : ''),
            'Route' => $codeExample->route
        ]"></x-enlighten-key-value>

    @includeWhen($codeExample->route_parameters, 'enlighten::block.parameters-table')
    @includeWhen($codeExample->request_input, 'enlighten::block.request-input-table')

    <x-enlighten-key-value :items="$codeExample->request_headers" title="Request Headers"></x-enlighten-key-value>
</x-enlighten-info-panel>