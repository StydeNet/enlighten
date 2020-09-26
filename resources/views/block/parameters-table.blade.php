<table class="bg-gray-800 p-4 overflow-hidden text-gray-100 w-full">
    <thead>
    <tr class="bg-gray-500">
        <th class="py-2 text-sm text-left text-gray-800 px-4 text-sm font-normal">Parameter</th>
        <th class="py-2 text-sm text-left text-gray-800 px-4 text-sm font-normal">Pattern</th>
        <th class="py-2 text-sm text-left text-gray-800 px-4 text-sm font-normal">Optional</th>
    </tr>
    </thead>
    <tbody>
    @foreach($codeExample->route_parameters as $parameter)
        <tr class="break-all">
            <td class="py-2 px-4 font-thin text-gray-200">{{ $parameter['name'] }}</td>
            <td class="py-2 px-4 font-thin text-teal-300">{{ $parameter['pattern'] }}</td>
            <td class="py-2 px-4 font-thin text-teal-300">{{ $parameter['optional'] ? 'Optional' : 'Required' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
