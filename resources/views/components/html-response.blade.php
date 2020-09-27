<div class="w-full" x-data="{active: 'preview'}">
    <div class="flex mb-2 space-x-4">
        <button x-on:click="active='preview'" x-bind:class="{'bg-teal-700 text-teal-900': (active !== 'preview'), 'bg-teal-300 text-teal-800': (active === 'preview')}" class="px-2 py-1 text-sm rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100">Preview</button>
        <button x-on:click="active='html'" x-bind:class="{'bg-teal-700 text-teal-900': (active !== 'html'), 'bg-teal-300 text-teal-800': (active === 'html')}" class="px-2 py-1 text-sm text-gray-200 rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100">HTML</button>
        @if(!empty($blade))
        <button x-on:click="active='blade'" x-bind:class="{'bg-teal-700 text-teal-900': (active !== 'blade'), 'bg-teal-300 text-teal-800': (active === 'blade')}" class="px-2 py-1 text-sm text-gray-200 rounded-lg focus:outline-none hover:bg-teal-500 transition-all ease-in-out duration-100">Blade</button>
        @endif
    </div>
    <div class="w-full rounded-lg overflow-hidden" x-show="active === 'preview'">
        <iframe class="h-full w-full bg-white" srcdoc="{{ $html }}"></iframe>
    </div>
    <div class="w-full rounded-lg overflow-hidden" x-show="active === 'html'">
        <pre style="margin:0;"
             class="h-full w-full bg-gray-300 rounded-lg overflow-hidden"
        ><code class="language-html">{{ $html }}</code></pre>
    </div>
    @if(!empty($blade))
    <div class="w-full rounded-lg overflow-hidden" x-show="active === 'blade'">
        <pre style="margin:0;"
             class="h-full w-full bg-gray-300 rounded-lg overflow-hidden"
        ><code class="language-html">{{ $blade }}</code></pre>
    </div>
    @endif
</div>
