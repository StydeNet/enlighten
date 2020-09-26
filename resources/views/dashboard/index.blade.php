@extends('enlighten::layout.main')

@section('content')

    <div class="container mx-auto my-12 h-screen px-4">
        <nav class="border-b border-gray-500">
            <ul class="flex space-x-4">
                @foreach($tabs as $tabName)
                    <li
                        class="border-b-2 hover:border-teal-400 border-transparent transition-all ease-in-out duration-200 {{ $tabName === $suite ?  'border-teal-400' : '' }}">
                           <a href="{{ route('enlighten.dashboard', ['suite' => $tabName]) }}" class="py-4 px-2 text-gray-100 focus:outline-none">{{ $tabName }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="grid grid-cols-4 gap-4 mt-4">
            @foreach($modules as $module)
            <div class="rounded-lg bg-white overflow-hidden">
                <div class="flex p-4 justify-between items-center w-full border-b border-gray-300 bg-gray-200">
                    <span class="font-semibold text-gray-700">{{ $module->getName() }}</span>
                    <span class="rounded-full text-xs text-green-800 bg-green-300 px-4 py-1 inline-flex">{{ $module->getGroup()->count() }}</span>
                </div>
                <ul>
                    @foreach($module->getGroup() as $group)
                    <li>
                        <a href="{{ route('enlighten.group.show', ['group' => $group]) }}"
                           class="block py-2 px-4 text-gray-700 hover:text-teal-500 hover:bg-gray-100 transition-all ease-in-out duration-100"
                        >{{ $group->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach

        </div>
    </div>

@endsection
