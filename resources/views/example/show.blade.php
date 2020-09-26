@extends('enlighten::layout.main')

@section('content')
<div class="py-12">
    @foreach($group->examples as $codeExample)
        <div class="container mx-auto grid grid-cols-2 gap-4 w-full">
            <div class="p-4">
                <div class="pb-4 border-b border-gray-300 mb-8">
                    <h1 class="text-xl text-gray-100 font-semibold">{{ $codeExample->title }}</h1>
                    <p class="text-gray-100">{{ $codeExample->description }}</p>
                </div>

                @include('enlighten::block.request-info')

                @include('enlighten::block.response-info')
            </div>

            @include('enlighten::block.preview')
        </div>
    @endforeach
</div>
@endsection
