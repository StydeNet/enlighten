<x-enlighten-main-layout>
    @empty($area)
        <x-slot name="title">{{ trans('enlighten::messages.all_endpoints') }}</x-slot>
    @else
        <x-slot name="title">{{ $area->title }}</x-slot>
    @endif

    @foreach($endpoints as $endpoint)
        <h2>
            {{ $endpoint->method }} {{ $endpoint->route }}
            {{ $endpoint->title }}

            -

            <a href="{{ $endpoint->mainRequest->example->url }}">
                {{ $endpoint->mainRequest->example->title }}
            </a>
        </h2>

        <ul>
            @foreach ($endpoint->additionalRequests as $additionalRequest)
                <li>
                    {{ $additionalRequest->request_method }} {{ $additionalRequest->route }}
                    <a href="{{ $additionalRequest->example->url }}">
                        {{ $additionalRequest->example->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</x-enlighten-main-layout>
