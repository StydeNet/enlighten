<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value :items="$routeInfo"></x-enlighten-key-value>

    @if($request->route_parameters)
        <x-enlighten-route-parameters-table :parameters="$request->route_parameters"></x-enlighten-route-parameters-table>
    @endif

    @if($request->request_input)
        <x-enlighten-request-input-table :input="$request_input"></x-enlighten-request-input-table>
    @endif

    @if($request->request_headers)
        <x-enlighten-key-value :items="$request->request_headers" title="Request Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
