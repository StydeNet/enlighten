<div x-data="{active: '{{ $tabs_collection->keys()->first() }}'}" class="flex flex-col h-full">
        <div class="flex space-x-4 mb-4">
            @foreach($tabs_collection as $name => $title)
                @if(isset($$name) && $$name instanceof \Illuminate\Support\HtmlString && !$$name->isEmpty())
                <button
                        x-on:click="active='{{ $name }}'"
                        x-bind:class="{'bg-teal-700 text-teal-900': (active !== '{{ $name }}'), 'bg-teal-300 text-teal-800': (active === '{{ $name }}')}"
                        class="px-2 py-1 text-sm rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100"
                >{{ $title }}</button>
                @endif
            @endforeach
        </div>

        <div class="w-full h-full">
            @foreach($tabs_collection as $name => $title)
                @if(isset($$name) && $$name instanceof \Illuminate\Support\HtmlString && !$$name->isEmpty())
                     <div x-cloak x-show="active === '{{ $name }}' " class="h-full">
                         {!! $$name !!}
                     </div>
                @endif
            @endforeach
        </div>
</div>