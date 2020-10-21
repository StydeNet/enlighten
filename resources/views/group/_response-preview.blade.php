<div class="h-full">
    @if($httpData->response_type === 'JSON')
        <x-enlighten-pre
            language="json"
            :code="json_encode($httpData->response_body, JSON_PRETTY_PRINT)"/>
    @elseif($httpData->response_type === 'HTML')
        <x-enlighten-html-response :http-data="$httpData" />
    @endif
</div>
