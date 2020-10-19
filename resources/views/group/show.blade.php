<x-enlighten-main-layout>
    <div class="w-full">
        @if($group->description)
            <p class="text-gray-100 mb-8 text-lg">
                {{ $group->description }}
            </p>
        @endif

        <div class="flex space-x-4">
            <div class="2/12">
                <x-enlighten-content-table :examples="$group->examples"></x-enlighten-content-table>
            </div>
            <div class="flex-1">
                @foreach($group->examples as $example)
                    <x-enlighten-code-example :example="$example"/>
                @endforeach
            </div>
        </div>
        <x-enlighten-scroll-to-top/>
    </div>
</x-enlighten-main-layout>
