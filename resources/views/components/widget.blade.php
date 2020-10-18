@props(['name', 'prefetch' => true, 'query' => [], 'after' => ''])
<div
    {{ $attributes->except('x-init', 'x-data', 'class') }}
    @if($prefetch) x-init="setLoading($refs); fetchData($refs, afterFetch);" @endif
     x-data="{
        fetchData($refs, callback) {
            fetch('/enlighten/widget/{{$name}}?{{http_build_query($query)}}')
                    .then(response => response.text())
                    .then(html => { $refs.content.innerHTML = html; callback($refs) });
        },
        setLoading($refs) {
            $refs.content.innerHTML = 'Loading Spinner...';
        },
        afterFetch($refs) {
            {{ $after }}
        }
    }"
>
    <div x-ref="content"></div>
</div>
