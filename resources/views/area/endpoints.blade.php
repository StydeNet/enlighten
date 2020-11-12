    @empty($area)
{{--        <x-slot name="title">--}}
            {{ trans('enlighten::messages.all_endpoints') }}
{{--        </x-slot>--}}
    @else
{{--        <x-slot name="title">--}}
            {{ $area->title }}
{{--        </x-slot>--}}
    @endif

    @foreach($modules as $module)
        <h1>{{ $module->name }}</h1>

        @foreach($module->groups as $group)
            <h3>
                {{ $group->method }} {{ $group->route }}
                {{ $group->title }}

                -

                <a href="{{ $group->mainRequest->example->url }}">
                    {{ $group->mainRequest->example->title }}
                </a>

                {{ $group->mainRequest->response_status }} {{ $group->mainRequest->response_types }}
            </h3>

            <ul>
                @foreach ($group->additionalRequests as $additionalRequest)
                    <li>
                        <a href="{{ $additionalRequest->example->url }}">
                            {{ $additionalRequest->example->title }}
                        </a>

                        {{ $additionalRequest->response_status }} {{ $additionalRequest->response_type }}
                    </li>
                @endforeach
            </ul>
        @endforeach
    @endforeach
