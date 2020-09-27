@extends('enlighten::layout.main')

@section('content')
    <div class="w-full">
        @include('enlighten::group._content-table')

        @foreach($group->examples as $codeExample)
            @include('enlighten::group._code-example')
        @endforeach

        <x-enlighten-scroll-to-top/>
    </div>
@endsection
