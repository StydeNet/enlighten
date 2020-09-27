<h2 id="{{$codeExample->method_name}}" class="text-2xl text-gray-100 font-semibold block w-full my-4">{{ $codeExample->title }}</h2>

<div class="grid grid-cols-2 gap-4 w-full mb-12">
    <div>
        <p class="text-gray-100 mb-4">{{ $codeExample->description }}</p>

        @include('enlighten::group._request-info')

        <span class="mb-8 w-full block"></span>

        @include('enlighten::group._response-info')
    </div>

    @include('enlighten::group._response-preview')
</div>

<span class="my-8 w-full block border-b-2 border-gray-600"></span>