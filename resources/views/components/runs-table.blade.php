<table class="w-full rounded-lg overflow-hidden bg-white">
    <thead>
        <tr class="uppercase text-gray-700 text-sm bg-gray-200 border-b border-gray-300">
            <td class="px-6 py-4">Branch / Commit</td>
            <td class="px-6 py-4">Date</td>
            <td colspan="2" class="px-6 py-4">Stats</td>
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
                    <a href="{{ route('enlighten.run.show', $run->id) }}" class="text-teal-800 hover:text-teal-600">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
