<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value :items="$routeInfo"></x-enlighten-key-value>

    @if($example->http_data->route_parameters)
        <x-enlighten-parameters-table :parameters="$example->http_data->route_parameters"></x-enlighten-parameters-table>
    @endif

    @if($example->http_data->request_input)
        <x-enlighten-request-input-table :input="$example->http_data->request_input"></x-enlighten-request-input-table>
    @endif

    @if($example->http_data->request_headers)
        <x-enlighten-key-value :items="$example->http_data->request_headers" title="Request Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
