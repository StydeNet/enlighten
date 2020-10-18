<span id="{{ $example->method_name }}"></span>

<div class="flex items-center bg-gray-800 my-1 px-2 mb-4">

    <x-enlighten-status-badge size="8" :model="$example"/>

    <h2 class="text-xl text-gray-100 semibold block w-full my-3 flex items-center">
        {{ $failed ? ucwords($example->test_status) . ':' : '' }} {{ $example->title }}
        <x-enlighten-edit-button :file="$example->file_link"/>
    </h2>

</div>

<div class="w-full mb-12" x-data="{active: 'requests'}">
    <p class="text-gray-100 mb-4">{{ $example->description }}</p>

    <x-enlighten-dynamic-tabs :tabs="['Requests', 'SQL', 'Exception']">
        <x-slot name="requests">
            @if($example->is_http)
                @foreach($example->http_data as $http_data)
                    <div class="grid md:grid-cols-2 space-y-8 md:space-y-0 md:space-x-6 w-full h-full">
                        <div>
                            <x-enlighten-request-info :http-data="$http_data" />
                            <span class="mb-8 w-full block"></span>

                            <x-enlighten-response-info :http-data="$http_data" />
                            <span class="mb-8 w-full block"></span>

                            @if($http_data->session_data)
                                <x-enlighten-info-panel>
                                    <x-slot name="title">Session data</x-slot>
                                    <x-enlighten-pre language="json" :code="json_encode($http_data->session_data, JSON_PRETTY_PRINT)"/>
                                </x-enlighten-info-panel>
                            @endif
                        </div>
                        <div>
                            @if($example->exception->exists)
                                <x-enlighten-iframe srcdoc="{{ $http_data->response_preview }}"/>
                            @else
                                <x-enlighten-response-preview :http-data="$http_data"/>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </x-slot>
        @if($example->queries->isNotEmpty())
            <x-slot name="sql">
                <x-enlighten-widget
                    title="Database Queries"
                    name="sql-queries"
                    :query="['example' => $example->id]"
                    after="Prism.highlightAllUnder($refs.content)"
                    :expansible="true" :collapsed="true"></x-enlighten-widget>
            </x-slot>
        @endif
        @if($example->exception)
            <x-slot name="exception">
                <x-enlighten-exception-info :exception="$example->exception"/>
            </x-slot>
        @endif
    </x-enlighten-dynamic-tabs>
</div>
