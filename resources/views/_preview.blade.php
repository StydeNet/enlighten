<div class="p-4">
    @if($codeExample->response_type === 'JSON')
        <x-json-response :json="$codeExample->response_body"></x-json-response>
    @elseif($codeExample->response_type === 'HTML')
        <x-html-response :html="$codeExample->response_body" :blade="$codeExample->response_template"></x-html-response>
    @endif
</div>
