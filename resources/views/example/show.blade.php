<x-enlighten-main-layout>

    <x-slot name="title">
        <div class="flex">
            <x-enlighten-status-badge size="8" :model="$example"/>
            {{ $example->title }}
            <x-enlighten-edit-button :file="$example->file_link"/>
        </div>
    </x-slot>

    @if($example->description)
        <p class="text-gray-100 mb-4 bg-gray-800 p-4 rounded-md">{{ $example->description }}</p>
    @endif

    @if($example->is_http)
        <x-enlighten-dynamic-tabs :tabs="$example_tabs->pluck('title', 'key')->toArray()">
            @foreach($example_tabs as $tab)
                <x-slot :name="$tab['key']">
                    <div class="grid md:grid-cols-2 space-y-8 md:space-y-0 md:space-x-6 w-full h-full">
                        <div>
                            <x-enlighten-request-info :http-data="$tab['http_data']" />
                            <span class="mb-8 w-full block"></span>

                            <x-enlighten-response-info :http-data="$tab['http_data']" />
                            <span class="mb-8 w-full block"></span>

                            @if($tab['http_data']->session_data)
                                <x-enlighten-info-panel>
                                    <x-slot name="title">Session data</x-slot>
                                    <x-enlighten-pre language="json" :code="json_encode($tab['http_data']->session_data, JSON_PRETTY_PRINT)"/>
                                </x-enlighten-info-panel>
                            @endif
                        </div>
                        <div class="h-full relative">
                            @if($example->exception->exists)
                                <x-enlighten-iframe srcdoc="{{ $tab['http_data']->response_preview }}"/>
                            @else
                                <x-enlighten-response-preview :http-data="$tab['http_data']"/>
                            @endif
                        </div>
                    </div>
                </x-slot>
            @endforeach
        </x-enlighten-dynamic-tabs>
    @endif
</x-enlighten-main-layout>

