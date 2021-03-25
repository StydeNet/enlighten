@if(!empty($example->provided_data_snippet))
<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <x-enlighten-info-panel>
        <x-slot name="title">{{ __('enlighten::messages.data_provided') }}{{ is_numeric($example->data_name) ? ' #' : ': ' }}{{ $example->data_name }}</x-slot>
        <div class="h-full p-4" x-data
             x-init="document.querySelectorAll('a.sf-dump-toggle').forEach((el, key) => key > 0 && el.click())">
            {!! $example->provided_data_snippet !!}
        </div>
    </x-enlighten-info-panel>
</div>
@endif
