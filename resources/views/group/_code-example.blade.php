<div class="flex items-center justify-between bg-gray-800 my-1 px-2 mb-4">
    <h2 id="{{ $codeExample->method_name }}" class="text-xl text-gray-100 semibold block w-full my-3 px-2">{{ $codeExample->title }}</h2>
    <x-enlighten-status-badge :status="$codeExample->test_status"/>
</div>

<div class="grid grid-cols-2 gap-4 w-full mb-12">
    <div>
        <p class="text-gray-100 mb-4">{{ $codeExample->description }}</p>
        @include('enlighten::group._request-info')

        <span class="mb-8 w-full block"></span>

        @include('enlighten::group._response-info')
    </div>

    @include('enlighten::group._response-preview')
</div>
