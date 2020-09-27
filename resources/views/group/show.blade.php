@extends('enlighten::layout.main')

@section('content')
<div class="w-full">
    <span class="text-teal-300 text-xl my-1">Table of contents</span>
    <ul class="block mt-4 mb-12 table" x-data>
        @foreach($group->examples as $codeExample)
            <li>
                <span class="flex items-center text-gray-100 hover:text-teal-500 transition-all ease-in-out duration-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <a href="#" x-on:click.prevent="document.getElementById('{{$codeExample->method_name}}').scrollIntoView({behavior: 'smooth'})" class="py-2 ml-2">{{ $codeExample->title }}</a>
                </span>
            </li>
        @endforeach
    </ul>

    @foreach($group->examples as $codeExample)
        <h2 id="{{$codeExample->method_name}}" class="text-2xl text-gray-100 font-semibold block w-full my-4">{{ $codeExample->title }}</h2>
        <div class="grid grid-cols-2 gap-4 w-full mb-12">
            <div>
                <p class="text-gray-100 mb-4">{{ $codeExample->description }}</p>
                @include('enlighten::block.request-info')
                <span class="mb-8 w-full block"></span>
                @include('enlighten::block.response-info')
            </div>

            @include('enlighten::block.preview')
        </div>
        <span class="my-8 w-full block border-b-2 border-gray-600"></span>
    @endforeach

    <span class="fixed bottom-0 my-8 mx-10 right-0">
        <a href="#" x-data x-on:click.prevent="document.getElementById('top').scrollIntoView({behavior: 'smooth'})" class="text-teal-100 bg-gray-500 rounded-full p-2 block">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
        </a>
    </span>
</div>
@endsection
