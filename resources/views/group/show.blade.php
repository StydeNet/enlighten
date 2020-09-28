@extends('enlighten::layout.main')

@section('content')
    <div class="w-full">
        @if($group->description)
            <p class="text-gray-100 mb-8 text-lg">
                {{ $group->description }}
            </p>
        @endif
        @include('enlighten::group._content-table')

        @foreach($group->examples as $codeExample)
            @include('enlighten::group._code-example')
        @endforeach

        <x-enlighten-scroll-to-top/>
    </div>
@endsection
