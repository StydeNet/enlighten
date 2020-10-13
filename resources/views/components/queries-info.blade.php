<x-enlighten-info-panel>
    <x-slot name="title">Database Queries</x-slot>
    @foreach($example->queries as $query)
        <x-enlighten-pre language="sql" :code="$query->sql"></x-enlighten-pre>
        <x-enlighten-key-value :items="$query->bindings"></x-enlighten-key-value>
    @endforeach
</x-enlighten-info-panel>