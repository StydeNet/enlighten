<div class="rounded-lg bg-white overflow-hidden">
    <div class="flex p-4 justify-between items-center w-full border-b border-gray-300 bg-gray-200">
        <span class="font-semibold text-gray-700">{{ $module->getName() }}</span>
        <span class="rounded-full text-xs text-green-800 bg-green-300 px-4 py-1 inline-flex">{{ $module->getGroup()->count() }}</span>
    </div>
    <ul>
        @foreach($module->getGroup() as $group)
            <li>
                <a href="{{ route('enlighten.group.show', ['suite' => $active, 'group' => $group]) }}"
                   class="block py-2 px-4 text-gray-700 hover:text-teal-500 hover:bg-gray-100 transition-all ease-in-out duration-100"
                >{{ $group->title }}</a>
            </li>
        @endforeach
    </ul>
</div>