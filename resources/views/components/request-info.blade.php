<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value :items="$routeInfo"></x-enlighten-key-value>

    @if($http_data->route_parameters)
        <x-enlighten-route-parameters-table :parameters="$http_data->route_parameters"></x-enlighten-route-parameters-table>
    @endif

    @if($http_data->request_input)
        <x-enlighten-request-input-table :input="$http_data->request_input"></x-enlighten-request-input-table>
    @endif

    @if($http_data->request_headers)
        <x-enlighten-key-value :items="$http_data->request_headers" title="Request Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
