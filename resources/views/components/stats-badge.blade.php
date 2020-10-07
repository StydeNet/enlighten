<span class="rounded-full text-xs text-{{ $color }}-800 bg-{{ $color }}-300 px-3 py-1 inline-flex">
    @if ($color == 'green')
        {{ $total }}
    @else
        {{ $positive }} / {{ $total }}
    @endif
</span>
