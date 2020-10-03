<div>
    @if($codeExample->http_data->response_type === 'JSON')
        <x-enlighten-json-response :json="$codeExample->http_data->response_body"></x-enlighten-json-response>
    @elseif($codeExample->http_data->response_type === 'HTML')
        <x-enlighten-html-response
                :http-data="$codeExample->http_data"
        ></x-enlighten-html-response>
    @endif
</div>
