@extends('enlighten::layout.main')

@section('content')
<div class="container mx-auto py-12">
    <span class="block w-full border-b border-gray-300 py-4 mb-8">
        <h1 class="text-3xl text-gray-100">{{ $group->title }}</h1>
    </span>
    @foreach($group->examples as $codeExample)
        <div class="grid grid-cols-2 gap-4 w-full mb-12">
            <div>
                <h2 class="text-xl text-gray-100 font-semibold">{{ $codeExample->title }}</h2>
                <p class="text-gray-100 my-4">{{ $codeExample->description }}</p>

                @include('enlighten::block.request-info')
                <span class="mb-8 w-full block"></span>
                @include('enlighten::block.response-info')
            </div>

            @include('enlighten::block.preview')
        </div>
        <span class="my-8 w-full block border-b-2 border-gray-600"></span>
    @endforeach
</div>
@endsection
