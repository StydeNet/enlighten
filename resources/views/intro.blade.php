@extends('enlighten::layout.main')

@section('content')

<div class="w-full mx-auto bg-gray-200 rounded-lg pt-16">
    <div class="prose pb-8 w-full mx-auto block">
        {!! $content !!}
    </div>
</div>

@endsection