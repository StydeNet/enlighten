<ul class="origin-top-right bg-white rounded-md shadow-md block divide-y divide-gray-300 overflow-hidden">
    @foreach($examples as $example)
        <li>
            <a x-on:click="open = false;" href="{{ $example->url  }}"
               class="block text-sm px-3 py-3 text-gray-700 hover:bg-gray-100">
                <span class="font-semibold block">{{ ucwords($example->group->area) }} / {{ $example->group->title }}</span>
                 {{ $example->title }}
            </a>
        </li>
    @endforeach
</ul>
