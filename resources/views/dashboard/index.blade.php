@extends('enlighten::layout.main')

@section('content')

    <div class="container mx-auto my-12 h-screen">
        <nav class="border-b border-gray-500">
            <ul class="flex space-x-4">
                <li class="py-4 px-2 text-gray-100 border-b-2 border-teal-400">Features</li>
                <li class="py-4 px-2 text-gray-100 border-b-2 border-transparent">Unit</li>
                <li class="py-4 px-2 text-gray-100 border-b-2 border-transparent">Api</li>
                <li class="py-4 px-2 text-gray-100 border-b-2 border-transparent">Browser</li>
            </ul>
        </nav>

        <div class="grid grid-cols-4 gap-4 mt-4">
            @foreach(range(0, 8) as $i)
            <div class="rounded-lg bg-white overflow-hidden">
                <div class="flex p-4 justify-between items-center w-full border-b border-gray-300 bg-gray-200">
                    <span class="font-semibold text-gray-700">Users</span>
                    <span class="rounded-full text-sm text-green-800 bg-green-300 px-4 py-1 inline-flex">25</span>
                </div>
                <ul>
                    @foreach(range(0, rand(0, 6)) as $i)
                    <li class="py-2 px-4 hover:bg-gray-100">
                        <a href="#" class="text-gray-700  hover:text-teal-500 transition-color ease-in-out duration-100">List user Test</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach

        </div>
    </div>

@endsection
