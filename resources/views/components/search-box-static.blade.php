<div {{ $attributes }}
     x-data='{
         open: true,
         results: [],
         index: {}
    }'
     x-on:data-loaded="index = $event.detail"
     x-init="
        fetch('/docs/search.json')
            .then(response => response.json().then(response => { $dispatch('data-loaded', response.items) }))
        ">
    <input
        x-on:input.debounce="
            results = index.filter(el => `${el.title} ${el.section}`.toLowerCase().includes($event.target.value.toLowerCase())).slice(0, 5);
            open = true;"
        class="bg-gray-900 w-full text-sm placeholder-gray-300 focus:placeholder-gray-600 text-gray-300 rounded-md focus:outline-none focus:bg-gray-100 focus:text-gray-800 px-3 py-3"
        placeholder="Search"
        type="text"
        role="search"
        name="Search">
    <div x-cloak x-ref="dropdown" x-show-="open" x-on:click.away="open = false"class="absolute block w-full my-1">
        <ul class="origin-top-right bg-white rounded-md shadow-md block divide-y divide-gray-300 overflow-hidden">
            <template x-for="(item, index) in results" :key="index">
                <li>
                    <a x-on:click="open = false;" x-bind:href="item.url"
                       class="block text-sm px-3 py-3 text-gray-700 hover:bg-gray-100">
                        <span class="font-semibold block" x-text="item.section"></span>
                        <span x-text="item.title"></span>
                    </a>
                </li>
            </template>
        </ul>
    </div>
</div>
