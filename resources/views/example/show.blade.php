<x-enlighten-main-layout>

    <x-slot name="top">
        <x-enlighten-breadcrumbs :segments="[
            route('enlighten.run.show', ['run' => $example->group->run_id, 'area' => $example->group->area]) => ucwords($example->group->area),
            $example->group->url => $example->group->title
        ]"></x-enlighten-breadcrumbs>
    </x-slot>

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

    @if($example->snippets->isNotEmpty())
        @foreach($example->snippets as $snippet)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <x-enlighten-info-panel>
                    <x-slot name="title">{{ __('enlighten::messages.Snippet') }}</x-slot>
                    <x-enlighten-pre language="php" :code="$snippet->code"></x-enlighten-pre>
                </x-enlighten-info-panel>
                <x-enlighten-info-panel>
                    <x-slot name="title">{{ __('enlighten::messages.Output') }}</x-slot>
                    <div class="h-full" x-data
                         x-init="document.querySelectorAll('a.sf-dump-toggle').forEach((el, key) => key > 0 && el.click())">
                        {!! $snippet->result_code !!}
                    </div>
                </x-enlighten-info-panel>
            </div>
        @endforeach
    @endif

    @if($example->is_http)
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
                <x-enlighten-dynamic-tabs :tabs="$example_tabs->pluck('title', 'key')->toArray()">
                    @foreach($example_tabs as $tab)
                        <x-slot :name="$tab['key']">
                            <div class="grid md:grid-cols-2 space-y-8 md:space-y-0 md:space-x-6 w-full h-full">
                                <div>
                                    <x-enlighten-request-info :request="$tab['requests']" />
                                    <span class="mb-8 w-full block"></span>

                                    <x-enlighten-response-info :request="$tab['requests']" />
                                    <span class="mb-8 w-full block"></span>

                                    @if($tab['requests']->session_data)
                                        <x-enlighten-info-panel>
                                            <x-slot name="title">{{ __('enlighten::messages.Session data') }}</x-slot>
                                            <x-enlighten-pre language="json" :code="json_encode($tab['requests']->session_data, JSON_PRETTY_PRINT)"/>
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
    @endif
</x-enlighten-main-layout>

