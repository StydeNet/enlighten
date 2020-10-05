<div class="rounded-lg bg-white overflow-hidden">
    <div class="flex p-4 justify-between items-center w-full border-b border-gray-300 bg-gray-200">
        <span class="font-semibold text-lg text-gray-700">{{ $module->name }}</span>
        <span class="rounded-full text-xs text-{{ $module->getStatus() === 'passed' ? 'green' : ($module->getStatus() === 'warned' ? 'yellow' : 'red')  }}-800 bg-{{ $module->getStatus() === 'passed' ? 'green' : ($module->getStatus() === 'warned' ? 'yellow' : 'red')  }}-300 px-4 py-1 inline-flex">{{ $module->getPassingTestsCount() }} / {{ $module->getTestsCount() }}</span>
    </div>
    <ul class="py-4">
        @foreach($module->groups as $group)
            <li>
                <a href="{{ route('enlighten.group.show', ['suite' => $suite->slug, 'run' => request()->route('run'), 'group' => $group]) }}"
                   class="block py-2 px-4 text-gray-700 hover:text-teal-500 hover:bg-gray-100 transition-all ease-in-out duration-100"
                >{{ $group->title }}</a>
            </li>
        @endforeach
    </ul>
</div>