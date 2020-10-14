@props(['language', 'code'])

<div {{ $attributes }}>
    <pre style="margin:0;"
         class="h-full w-full bg-gray-300 rounded-lg overflow-hidden"
    ><code class="language-{{ $language }}">{{ $code }}</code></pre>
</div>