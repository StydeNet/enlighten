<div class="p-4">
    @if($codeExample->response_type === 'JSON')
        <x-enlighten-json-response :json="$codeExample->response_body"></x-enlighten-json-response>
    @elseif($codeExample->response_type === 'HTML')
        <x-enlighten-html-response :html="$codeExample->response_body" :blade="$codeExample->response_template"></x-enlighten-html-response>
    @endif
</div>
