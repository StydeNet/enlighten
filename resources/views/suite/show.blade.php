@extends('enlighten::layout.main')

@section('content')
    <div class="w-full mx-auto">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4">
            @foreach($modules as $module)
                @include('enlighten::dashboard._module-panel')
            @endforeach
        </div>
    </div>
@endsection
