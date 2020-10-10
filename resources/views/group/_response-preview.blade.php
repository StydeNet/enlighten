<div>
    @if($codeExample->http_data->response_type === 'JSON')
        <x-enlighten-pre
                language="json"
                :code="json_encode($codeExample->http_data->response_body, JSON_PRETTY_PRINT)"
        />
    @elseif($codeExample->http_data->response_type === 'HTML')
        <x-enlighten-html-response :http-data="$codeExample->http_data" />
    @endif
</div>
