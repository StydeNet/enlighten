<x-enlighten-info-panel>
    <x-slot name="title">
        <span class="flex">
            {{ $exception->class_name }}: {{ $exception->message }}
            <x-enlighten-edit-button :file="$exception->file_link"/>
        </span>
    </x-slot>

    @if($exception->extra)
        <x-enlighten-pre language="json" :code="enlighten_json_prettify($exception->extra['errors'])"/>
        <span class="border-b border-gray-900 block w-full"></span>
    @endif

    @foreach($trace as $data)
        <div>
            @if(!empty($title))
                <span class="block bg-gray-500 py-2 text-sm text-left text-gray-800 px-4 text-sm font-normal w-full"
                >{{ $title }}</span>
            @endif

            <table class="bg-gray-800 p-4 rounded-lg text-gray-100 my-4 w-full" x-data="{show: false}">
                @if($data['file'] && $data['line'])
                    <tr>
                        <td class="font-light break-all text-teal-200 px-4">{{ $data['file'] }} : {{ $data['line'] }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="font-light break-all text-gray-300 px-4 flex items-center">
                        {{ $data['function'] }}
                        @if(!empty($data['args']))
                            <a href="#" class="ml-4" x-on:click.prevent="show = !show">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </a>
                        @endif
                    </td>
                </tr>
                @if(!empty($data['args']))
                    <tr>
                        <td class="font-light break-all text-teal-200 px-4">
                            <div class="w-full">
                                <div x-cloak x-show="show === true">
                                    <x-enlighten-pre language="json" :code="enlighten_json_prettify($data['args'])"/>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            </table>
        </div>

        @unless($loop->last)
            <span class="w-full block border-b border-gray-900 my-4"></span>
        @endunless
    @endforeach
</x-enlighten-info-panel>
