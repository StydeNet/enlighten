<div>
    @if($codeExample->http_data->response_type === 'JSON')
        <x-enlighten-json-response :json="$codeExample->http_data->response_body"></x-enlighten-json-response>
    @elseif($codeExample->http_data->response_type === 'HTML')
        <x-enlighten-html-response
                :html="$codeExample->http_data->response_body"
                :blade="$codeExample->http_data->response_template"
        ></x-enlighten-html-response>
    @endif
</div>
