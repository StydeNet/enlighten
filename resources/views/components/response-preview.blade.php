<div class="h-full">
    @if($request->response_type === 'JSON')
        <x-enlighten-pre language="json" :code="enlighten_json_prettify($request->response_body)" />
    @elseif($request->response_type === 'HTML')
        <x-enlighten-html-response :request="$request" />
    @endif
</div>
