<div class="bg-gray-800 rounded-md overflow-hidden mb-12">
    <span class="text-gray-100 text-xl bg-gray-700 px-4 py-2 flex w-full">Features</span>
    <ul class="block mt-0 table p-4 grid {{ $group->examples->count() > 4 ? 'lg:grid-cols-2' : '' }}" x-data>
        @foreach($group->examples as $codeExample)
            <li class="flex items-center text-gray-100 hover:text-teal-500 transition-all ease-in-out duration-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <a href="#"
                   x-on:click.prevent="document.getElementById('{{$codeExample->method_name}}').scrollIntoView({behavior: 'smooth'})"
                   class="py-2 ml-2 flex-1">{{ $codeExample->title }}</a>
            </li>
        @endforeach
    </ul>
</div>