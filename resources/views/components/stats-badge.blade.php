@props(['status', 'testsCount', 'passingTests', 'testsCount'])

<span class="rounded-full text-xs text-{{ $status === 'passed' ? 'green' : ($status === 'warned' ? 'yellow' : 'red')  }}-800 bg-{{ $status === 'passed' ? 'green' : ($status === 'warned' ? 'yellow' : 'red')  }}-300 px-3 py-1 inline-flex">
    @if ($status === 'passed')
        {{ $testsCount }}
    @else
        {{ $passingTests }} / {{ $testsCount }}
    @endif
</span>
