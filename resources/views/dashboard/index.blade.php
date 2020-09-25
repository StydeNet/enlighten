@extends('enlighten::layout.main')

@section('content')

    <div class="container mx-auto my-12 h-screen px-4">
        <nav class="border-b border-gray-500">
            <ul class="flex space-x-4">
                <li class="py-4 px-2 text-gray-100 border-b-2 border-teal-400">Features</li>
                <li class="py-4 px-2 text-gray-100 border-b-2 border-transparent">Unit</li>
                <li class="py-4 px-2 text-gray-100 border-b-2 border-transparent">Api</li>
                <li class="py-4 px-2 text-gray-100 border-b-2 border-transparent">Browser</li>
            </ul>
        </nav>

        <div class="grid grid-cols-4 gap-4 mt-4">
            @foreach($groups as $groupName => $group)
            <div class="rounded-lg bg-white overflow-hidden">
                <div class="flex p-4 justify-between items-center w-full border-b border-gray-300 bg-gray-200">
                    <span class="font-semibold text-gray-700">{{ $groupName }}</span>
                    <span class="rounded-full text-sm text-green-800 bg-green-300 px-4 py-1 inline-flex">{{ $group->count() }}</span>
                </div>
                <ul>
                    @foreach($group->all() as $example)
                    <li>
                        <a href="{{ route('enlighten.example.show', ['example' => $example]) }}"
                           class="block py-2 px-4 text-gray-700 hover:text-teal-500 hover:bg-gray-100 transition-all ease-in-out duration-100"
                        >{{ $example->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach

        </div>
    </div>

@endsection
