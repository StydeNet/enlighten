<div {{ $attributes->merge(['class' => 'bg-gray-800 rounded-md overflow-hidden']) }}>
    <span class="block w-full bg-gray-700 px-4 py-2">
        <h3 class="text-base text-gray-300">{{ $title }}</h3>
    </span>
    {!! $slot !!}
</div>
