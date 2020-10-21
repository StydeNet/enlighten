<table class="w-full rounded-lg overflow-hidden bg-white">
    <thead>
        <tr class="uppercase text-gray-700 text-sm bg-gray-200 border-b border-gray-300">
            <td class="px-6 py-4">Commit</td>
            <td class="px-6 py-4">Date</td>
            <td colspan="2" class="px-6 py-4">Stats</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($runs as $run)
            <tr class="{{ $loop->even ? 'bg-gray-100' : '' }} text-gray-700">
                <td class=" px-6 py-4 flex">
                    @if ($run->modified)
                        <span class="text-red-500 pr-2 text-xl">*</span>
                    @endif
                    {{ $run->head }}
                </td>
                <td class=" px-6 py-4 ">{{ $run->created_at->toDateTimeString() }}</td>
                <td class=" px-6 py-4 ">
                    <x-enlighten-stats-badge :model="$run" />
                </td>
                <td class=" px-6 py-4 ">
                    <a href="{{ route('enlighten.run.show', $run->id) }}" class="text-teal-500 hover:text-teal-600">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
