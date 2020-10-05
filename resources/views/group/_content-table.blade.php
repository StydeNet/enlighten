<div class="bg-gray-800 rounded-md overflow-hidden mb-12">
    <span class="text-gray-100 text-xl bg-gray-700 px-4 py-2 flex w-full">Features</span>

    <ul class="block mt-0 py-4 {{ $group->examples->count() > 4 ? 'md:columns-2' : '' }}"  x-data>
        @foreach($group->examples as $codeExample)
            <li class="items-center text-gray-100 hover:text-teal-500 transition-all ease-in-out duration-100">
                <span class="inline-grid">
                    <a href="#"
                       x-on:click.prevent="document.getElementById('{{ $codeExample->method_name }}').scrollIntoView({behavior: 'smooth'})"
                       class="py-2 ml-2 flex-1 flex items-center ">
                        <x-enlighten-status-badge size="6" :model="$codeExample"/>
                        {{ $codeExample->title }}
                    </a>
                </span>
            </li>
        @endforeach
    </ul>
</div>
