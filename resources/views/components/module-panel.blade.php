<div class="rounded-lg bg-white overflow-hidden">
    <div class="flex p-4 justify-between items-center w-full border-b border-gray-300 bg-gray-200">
        <span class="font-semibold text-lg text-gray-700">{{ $module->name }}</span>
        <x-enlighten-stats-badge :model="$module" />
    </div>
    <ul class="py-3">
        @foreach($module->groups as $group)
            <li>
                <a href="{{ route('enlighten.group.show', ['run' => request()->route('run'), 'group' => $group]) }}"
                   class="flex items-start py-2 px-4 text-gray-700 hover:bg-gray-200 transition-all ease-in-out duration-100">
                    <x-enlighten-status-badge size="6" :model="$group" /> {{ $group->title }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
