<div {{ $attributes }} x-data="{open: false}">
    <input
        x-on:input.debounce="fetch(`{{ $searchUrl }}?search=${$event.target.value}`)
                                    .then(response => response.text())
                                    .then(html => { $refs.dropdown.innerHTML = html; open = true })"
        class="bg-gray-900 w-full text-sm placeholder-gray-300 focus:placeholder-gray-600 text-gray-300 rounded-md focus:outline-none focus:bg-gray-100 focus:text-gray-800 px-3 py-3"
        placeholder="Search"
        type="text"
        role="search"
        name="Search">
    <div x-cloak x-ref="dropdown" x-show-="open" x-on:click.away="open = false"class="absolute block w-full my-1"></div>
</div>
