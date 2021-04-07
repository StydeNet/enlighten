<x-enlighten-main-layout>

    <x-slot name="top">
        <x-enlighten-example-breadcrumbs :example="$example" />
    </x-slot>

    <x-slot name="title">
        <div class="flex">
            <x-enlighten-status-badge size="8" :model="$example" />
            {{ $example->title }}
            <x-enlighten-edit-button :file="$example->file_link" />
        </div>
    </x-slot>

    @if($example->description)
        <p class="text-gray-100 mb-4 bg-gray-800 p-4 rounded-md">{{ $example->description }}</p>
    @endif

    <x-enlighten-example-snippets :snippets="$example->snippets" />

    <x-enlighten-example-tabs :example="$example" />
</x-enlighten-main-layout>

