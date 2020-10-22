<ul class="flex space-x-2 mt-4">
    @foreach($segments as $url => $title)
        <li>
            <a href="{{$url}}" class="text-gray-500 hover:text-gray-300 transition-all ease-in-out duration-100 flex items-center">
                {{ $title }}
                @if(!$loop->last)
                <svg class="w-4 h-4 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                @endif
            </a>
        </li>
    @endforeach
</ul>
