<x-enlighten-main-layout>
    <x-slot name="title">{{ $suite->title }} Area</x-slot>

    <div class="w-full mx-auto">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4">
            @forelse($modules as $module)
                <x-enlighten-module-panel :suite="$suite" :module="$module"></x-enlighten-module-panel>
            @empty
                <p class="text-white">
                    There are no examples in the "{{ $suite->title }}" area.
                </p>
            @endforelse
        </div>
    </div>
</x-enlighten-main-layout>
