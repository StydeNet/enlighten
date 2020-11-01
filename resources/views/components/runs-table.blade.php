<table class="w-full rounded-lg overflow-hidden bg-white">
    <thead>
        <tr class="uppercase text-gray-700 text-sm bg-gray-200 border-b border-gray-300">
            <td class="px-6 py-4">{{ __('enlighten::messages.branch_commit') }}</td>
            <td class="px-6 py-4">{{ __('enlighten::messages.date') }}</td>
            <td colspan="2" class="px-6 py-4">{{ __('enlighten::messages.stats') }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($runs as $run)
            <tr class="{{ $loop->even ? 'bg-gray-100' : '' }} text-gray-700">
                <td class="px-6 py-4 flex space-x-2">
                    <span class="font-bold">{{ $run->branch }}</span>
                    @if ($run->modified)<span class="text-red-500 text-xl">*</span>@endif
                    <span>{{ $run->head }}</span>
                </td>
                <td class=" px-6 py-4 ">{{ $run->created_at->toDateTimeString() }}</td>
                <td class=" px-6 py-4 ">
                    <x-enlighten-stats-badge :model="$run" />
                </td>
                <td class=" px-6 py-4 ">
                    <a href="{{ route('enlighten.area.show', ['run' => $run->id]) }}" class="text-teal-800 hover:text-teal-600">{{ __('enlighten::messages.view') }}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
