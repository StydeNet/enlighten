<div>
    @if(!empty($title))
    <span class="block bg-gray-500 py-2 text-sm text-left text-gray-800 px-4 text-sm font-normal w-full">{{ $title }}</span>
    @endif
    <ul class="bg-gray-800 p-4 rounded-lg text-gray-100">
        @foreach($items as $key => $value)
            <li>
                <span class="font-thin break-all text-gray-200">{{ $key }}:</span>
                <span class="font-thin break-all text-teal-300">{!! is_array($value) ? implode('<br/>', $value) : $value  !!}</span>
            </li>
        @endforeach
    </ul>
</div>
