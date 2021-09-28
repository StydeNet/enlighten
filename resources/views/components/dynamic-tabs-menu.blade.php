<div x-data="{active: '{{ $tabs_collection->keys()->first() }}'}" class="flex flex-col h-full py-2 px-2">
        @if($tabs_collection->count() > 1)
            <div class="flex space-x-4 mb-4 bg-gray-800">
                @foreach($tabs_collection as $name => $title)
                    @if(isset($$name) && $$name instanceof $htmlable && !$$name->isEmpty())
                    <button
                            x-on:click="active='{{ $name }}'"
                            x-bind:class="{
                                'border-teal-300': (active === '{{ $name }}'),
                                'border-gray-800': (active !== '{{ $name }}')
                            }"
                            class="mx-2 text-gray-100 px-2 py-3 border-b-2 text-sm focus:outline-none hover:border-teal-500 transition-all ease-in-out duration-100"
                    >{{ ucwords($title) }}</button>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="w-full h-full">
            @foreach($tabs_collection as $name => $title)
                @if(isset($$name) && $$name instanceof $htmlable && !$$name->isEmpty())
                     <div x-cloak x-show="active === '{{ $name }}' " class="h-full">
                         {!! $$name !!}
                     </div>
                @endif
            @endforeach
        </div>
</div>
