<div>
    <h3 class="font-normal text-gray-300 border-b border-gray-300 py-2">{{ $title }}</h3>
    <ul class="bg-gray-800 p-4 rounded-lg text-gray-100 mt-4">
        @foreach($items as $key => $value)
            <li>
                <span class="font-thin break-all text-gray-200">{{ $key }}:</span>
                <span class="font-thin break-all text-teal-300">{!! is_array($value) ? implode('<br/>', $value) : $value  !!}</span>
            </li>
        @endforeach
    </ul>
</div>
