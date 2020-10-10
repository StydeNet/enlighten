<x-enlighten-main-layout>
    <div class="w-full mx-auto">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4">
            @foreach($modules as $module)
                <x-enlighten-module-panel :suite="$suite" :module="$module"></x-enlighten-module-panel>
            @endforeach
        </div>
    </div>
</x-enlighten-main-layout>
