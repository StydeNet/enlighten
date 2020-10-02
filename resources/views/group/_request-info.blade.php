<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value
        :items="[
            'Method' => $codeExample->http_data->request_method,
            'Route' => $codeExample->http_data->route,
            'Example' => $codeExample->http_data->request_path . ($codeExample->http_data->request_query_parameters ? '?' . http_build_query($codeExample->http_data->request_query_parameters) : ''),
        ]"></x-enlighten-key-value>

    @includeWhen($codeExample->http_data->route_parameters, 'enlighten::group._parameters-table')
    @includeWhen($codeExample->http_data->request_input, 'enlighten::group._request-input-table')

    @if($codeExample->http_data->request_headers)
        <x-enlighten-key-value :items="$codeExample->http_data->request_headers" title="Request Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>