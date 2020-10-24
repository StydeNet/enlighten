<div>
    @if(!empty($title))
        <x-enlighten-panel-title>{{ $title }}</x-enlighten-panel-title>
    @endif

    <table class="bg-gray-800 p-4 rounded-lg text-gray-100 my-4">
        @foreach($items as $key => $value)
            <tr>
                <td class="font-thin whitespace-no-wrap text-gray-200 px-4">{{ $key }}: </td>
                <td class="font-thin break-all text-teal-300 pr-4"
                > {!! $value !!}</td>
            </tr>
        @endforeach
    </table>
</div>
