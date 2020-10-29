@props(['collapsed' => true])

<div x-data="{
        open: false,
        collapsed: {{ $collapsed }}
    }"
     x-bind:class="{
        'fixed top-0 left-0 w-screen h-screen p-8 bg-gray-800 z-50': open,
        'h-full relative': !open
     }"
     {{ $attributes }}
    >
    <button x-on:click="open = !open" type="button"
            x-bind:class="{'m-4': open}"
            class="text-white focus:outline-none p-1 m-1 bg-black opacity-25 hover:opacity-75 rounded-lg absolute top-0 right-0">
        <svg x-cloak x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <svg x-cloak x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
    </button>
    <div class="w-full h-full" x-bind:class="{'max-h-120 pb-0': (!open && collapsed), ' pb-8': open}">
        {{ $slot }}
    </div>
</div>
