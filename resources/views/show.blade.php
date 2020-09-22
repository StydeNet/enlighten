@extends('layout')

@section('content')
<div class="py-12">
    <div class="container mx-auto grid grid-cols-2 gap-4 w-full">
        <div class="p-4">
            <div class="pb-4 border-b border-gray-300 mb-8">
                <h1 class="text-xl text-gray-100 font-semibold">{{ $codeExample->title }}</h1>
                <p class="text-gray-100">{{ $codeExample->description }}</p>
            </div>

            @include('_request-info')

{{--            @include('_response-info')--}}
        </div>

{{--        @include('_preview')--}}
    </div>
</div>
@endsection
