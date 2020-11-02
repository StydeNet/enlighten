<x-enlighten-info-panel>
    <x-slot name="title">Request</x-slot>

    <x-enlighten-key-value :items="$routeInfo"></x-enlighten-key-value>

    @if($showRouteParameters)
        <x-enlighten-route-parameters-table :parameters="$request->route_parameters"></x-enlighten-route-parameters-table>
    @endif

    @if($showInput)
        <x-enlighten-request-input-table :input="$request_input"></x-enlighten-request-input-table>
    @endif

    @if($showHeaders)
        <x-enlighten-key-value :items="$request->request_headers" title="Request Headers"></x-enlighten-key-value>
    @endif
</x-enlighten-info-panel>
