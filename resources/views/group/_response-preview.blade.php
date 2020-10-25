<div class="h-full">
    @if($request->response_type === 'JSON')
        <x-enlighten-pre
            language="json"
            :code="json_encode($request->response_body, JSON_PRETTY_PRINT)"/>
    @elseif($request->response_type === 'HTML')
        <x-enlighten-html-response :request="$request" />
    @endif
</div>
