<x-enlighten-info-panel>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-enlighten-expansible-section>
        @foreach($queries as $query)
            <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
            <x-enlighten-key-value :items="$query->bindings"></x-enlighten-key-value>
        @endforeach
    </x-enlighten-expansible-section>
</x-enlighten-info-panel>
