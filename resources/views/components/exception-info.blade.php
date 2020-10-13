<x-enlighten-info-panel>
    <x-slot name="title">
        <span class="flex">
            {{ $exception->class_name }}: {{ $exception->message }}
            <x-enlighten-edit-button :file="$exception->file_link"/>
        </span>
    </x-slot>
    @foreach($trace as $data)
        <x-enlighten-key-value :items="$data"/>
        @unless($loop->last)
            <span class="w-full block border-b border-gray-900 my-4"></span>
        @endunless
    @endforeach
</x-enlighten-info-panel>
