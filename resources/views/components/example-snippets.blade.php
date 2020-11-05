@foreach($snippets as $snippet)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <x-enlighten-info-panel>
            <x-slot name="title">{{ __('enlighten::messages.snippet') }}</x-slot>
            <x-enlighten-pre language="php" :code="$snippet->code"/>
        </x-enlighten-info-panel>
        <x-enlighten-info-panel>
            <x-slot name="title">{{ __('enlighten::messages.output') }}</x-slot>
            <div class="h-full p-4" x-data
                 x-init="document.querySelectorAll('a.sf-dump-toggle').forEach((el, key) => key > 0 && el.click())">
                {!! $snippet->result_code !!}
            </div>
        </x-enlighten-info-panel>
    </div>
@endforeach
