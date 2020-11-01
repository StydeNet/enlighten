<x-enlighten-dynamic-tabs type="menu" :tabs="['requests', 'database', 'exception']">
    @if($example->exception->exists)
        <x-slot name="exception">
            <x-enlighten-exception-info :exception="$example->exception"></x-enlighten-exception-info>
        </x-slot>
    @endif
    @if($example->queries->isNotEmpty())
        <x-slot name="database">
            <x-enlighten-queries-info :example="$example"></x-enlighten-queries-info>
        </x-slot>
    @endif
    <x-slot name="requests">
        <x-enlighten-dynamic-tabs :tabs="$tabs->pluck('title', 'key')->toArray()">
            @foreach($tabs as $tab)
                <x-slot :name="$tab['key']">
                    <div class="grid md:grid-cols-2 space-y-8 md:space-y-0 md:space-x-6 w-full h-full">
                        <div>
                            <x-enlighten-request-info :request="$tab['requests']" />
                            <span class="mb-8 w-full block"></span>

                            <x-enlighten-response-info :request="$tab['requests']" />
                            <span class="mb-8 w-full block"></span>

                            @if($tab['requests']->session_data)
                                <x-enlighten-info-panel>
                                    <x-slot name="title">{{ __('enlighten::messages.session_data') }}</x-slot>
                                    <x-enlighten-pre language="json" :code="enlighten_json_prettify($tab['requests']->session_data)"/>
                                </x-enlighten-info-panel>
                            @endif
                        </div>
                        <div class="h-full relative">
                            @if($example->exception->exists)
                                <x-enlighten-iframe srcdoc="{{ $tab['requests']->response_preview }}"/>
                            @else
                                <x-enlighten-response-preview :request="$tab['requests']"/>
                            @endif
                        </div>
                    </div>
                </x-slot>
            @endforeach
        </x-enlighten-dynamic-tabs>
    </x-slot>
</x-enlighten-dynamic-tabs>
