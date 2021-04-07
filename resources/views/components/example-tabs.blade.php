<x-enlighten-dynamic-tabs type="menu" :tabs="['requests', 'database', 'exception']">
    @if($showException)
        <x-slot name="exception">
            <x-enlighten-exception-info :exception="$example->exception"></x-enlighten-exception-info>
        </x-slot>
    @endif
    @if($showQueries)
        <x-slot name="database">
            <x-enlighten-queries-info :example="$example"></x-enlighten-queries-info>
        </x-slot>
    @endif
    @if($showRequests)
    <x-slot name="requests">
        <x-enlighten-dynamic-tabs :tabs="$requestTabs->pluck('title', 'key')->toArray()">
            @foreach($requestTabs as $tab)
                <x-slot :name="$tab->key">
                    <div class="grid md:grid-cols-2 space-y-8 md:space-y-0 md:space-x-6 w-full h-full">
                        <div>
                            <x-enlighten-request-info :request="$tab->request" />
                            <span class="mb-8 w-full block"></span>

                            <x-enlighten-response-info :request="$tab->request" />
                            <span class="mb-8 w-full block"></span>

                            @if($tab->showSession)
                                <x-enlighten-info-panel>
                                    <x-slot name="title">{{ __('enlighten::messages.session_data') }}</x-slot>
                                    <x-enlighten-pre language="json" :code="enlighten_json_prettify($tab->request->session_data)" />
                                </x-enlighten-info-panel>
                            @endif
                        </div>
                        <div class="h-full relative">
                            @if($tab->showPreviewOnly)
                                <x-enlighten-iframe srcdoc="{{ $tab->request->response_preview }}" />
                            @else
                                <x-enlighten-response-preview :request="$tab->request" />
                            @endif
                        </div>
                    </div>
                </x-slot>
            @endforeach
        </x-enlighten-dynamic-tabs>
    </x-slot>
    @endif
</x-enlighten-dynamic-tabs>
