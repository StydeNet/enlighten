<x-enlighten-main-layout>
    <div class="w-full">
        @if($group->description)
            <p class="text-gray-100 mb-8 text-lg">
                {{ $group->description }}
            </p>
        @endif

        <x-enlighten-content-table :examples="$group->examples"></x-enlighten-content-table>

        @foreach($group->examples as $example)
            <x-enlighten-code-example :example="$example"/>
        @endforeach

        <x-enlighten-scroll-to-top/>
    </div>
</x-enlighten-main-layout>