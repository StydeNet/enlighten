@props(['name', 'prefetch' => true])
<div
    {{ $attributes->except('x-init', 'x-data', 'class') }}
    @if($prefetch) x-init="setLoading($refs); fetchData($refs);" @endif
     x-data="{
        fetchData($refs) {
            fetch('/enlighten/widget/{{$name}}')
                    .then(response => response.text())
                    .then(html => { $refs.content.innerHTML = html });
        },
        setLoading($refs) {
            $refs.content.innerHTML = 'Loading Spinner...';
        },
        loadingClass: 'w-full h-full animate-pulse bg-gray-400'
    }"
>
    <div x-ref="content"></div>
</div>
