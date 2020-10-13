<x-enlighten-dynamic-tabs :tabs="['preview', 'html', 'blade']">
    <x-slot name="preview">
        <x-enlighten-iframe srcdoc="{{ $httpData->response_preview }}"/>
    </x-slot>
    <x-slot name="html">
        <x-enlighten-pre :code="$httpData->response_body" language="html"></x-enlighten-pre>
    </x-slot>
    @if(!empty($httpData->response_template))
        <x-slot name="blade">
            <x-enlighten-pre :code="$httpData->response_template" language="html"></x-enlighten-pre>
        </x-slot>
    @endif
</x-enlighten-dynamic-tabs>
