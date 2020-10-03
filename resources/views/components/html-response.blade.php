<div class="w-full flex flex-col h-full" x-data="{active: 'preview'}">
    <div class="flex mb-2 space-x-4">
        <button x-on:click="active='preview'" x-bind:class="{'bg-teal-700 text-teal-900': (active !== 'preview'), 'bg-teal-300 text-teal-800': (active === 'preview')}" class="px-2 py-1 text-sm rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100">Preview</button>
        <button x-on:click="active='html'" x-bind:class="{'bg-teal-700 text-teal-900': (active !== 'html'), 'bg-teal-300 text-teal-800': (active === 'html')}" class="px-2 py-1 text-sm text-gray-200 rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100">HTML</button>
        @if(!empty($httpData->response_template))
        <button x-on:click="active='blade'" x-bind:class="{'bg-teal-700 text-teal-900': (active !== 'blade'), 'bg-teal-300 text-teal-800': (active === 'blade')}" class="px-2 py-1 text-sm text-gray-200 rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100">Blade</button>
        @endif
    </div>

    <div class="w-full flex-1 rounded-lg overflow-hidden" x-show="active === 'preview'">
        @if($httpData->has_redirection_status)
            <span class="bg-white p-4 w-full flex rounded-lg">
                Redirecting to {{ $httpData->redirection_location }}
            </span>
        @else
            <iframe class="h-full w-full bg-white" srcdoc="{{ $httpData->response_body }}"></iframe>
        @endif
    </div>

    <div class="w-full flex-1 rounded-lg overflow-hidden" x-show="active === 'html'">
        <pre style="margin:0;"
             class="h-full w-full bg-gray-300 rounded-lg overflow-hidden"
        ><code class="language-html">{{ $httpData->response_body }}</code></pre>
    </div>

    @if(!empty($httpData->response_template))
        <div class="w-full flex-1 rounded-lg overflow-hidden" x-show="active === 'blade'">
            <pre style="margin:0;"
                 class="h-full w-full bg-gray-300 rounded-lg overflow-hidden"
            ><code class="language-html">{{ $httpData->response_template }}</code></pre>
        </div>
    @endif
</div>
