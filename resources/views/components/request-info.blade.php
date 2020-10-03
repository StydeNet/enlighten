@props(['example'])

<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value
        :items="[
            'Method' => $example->http_data->request_method,
            'Route' => $example->http_data->route,
            'Example' => $example->http_data->request_path . ($example->http_data->request_query_parameters ? '?' . http_build_query($example->http_data->request_query_parameters) : ''),
        ]"></x-enlighten-key-value>

    @if($example->http_data->route_parameters)
        <x-enlighten-parameters-table :example="$example"></x-enlighten-parameters-table>
    @endif

    @if($example->http_data->request_input)
        <x-enlighten-request-input-table :example="$example"></x-enlighten-request-input-table>
    @endif

    @if($example->http_data->request_headers)
        <x-enlighten-key-value :items="$example->http_data->request_headers" title="Request Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>