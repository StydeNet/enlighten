<div class="flex items-center justify-between bg-gray-800 my-1 px-2 mb-4">
    <x-enlighten-status-badge size="8" :example="$codeExample"/>
    <h2 id="{{ $codeExample->method_name }}" class="text-xl text-gray-100 semibold block w-full my-3">
        @unless($codeExample->passed)
            {{ ucwords($codeExample->test_status) . ':' }}
        @endunless
            {{ $codeExample->title }}
       </h2>
</div>

<div class="grid grid-cols-2 gap-4 w-full mb-12">
    <div>
        <p class="text-gray-100 mb-4">{{ $codeExample->description }}</p>
        @if($codeExample->is_http)
            <x-enlighten-request-info :example="$codeExample"></x-enlighten-request-info>
            <span class="mb-8 w-full block"></span>
            <x-enlighten-response-info :example="$codeExample"></x-enlighten-response-info>
        @endif
    </div>

    @include('enlighten::group._response-preview')
</div>
