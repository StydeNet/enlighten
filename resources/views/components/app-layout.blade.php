<div class="h-screen flex overflow-hidden bg-gray-100" x-data="{open: false}">
    <div x-cloal x-show="open" class="md:hidden">
        <div class="fixed inset-0 flex z-40">
            <div x-show="open" class="fixed inset-0"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 xtransition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
            >
                <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
            </div>
            <div x-show="open"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 xtransition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-gray-800">
                <div class="absolute top-0 right-0 -mr-14 p-1">
                    <button x-on:click="open = !open" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600" aria-label="Close sidebar">
                        <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-shrink-0 flex items-center px-4">
                    <span class="rounded-full text-teal-100 bg-teal-500 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    <span class="font-bold text-gray-100 text-2xl pl-4">Enlighten</span>
                </div>
                <div class="mt-5 flex-1 h-0 overflow-y-auto">
                    <nav class="px-2 space-y-1">
                        <a href="{{ route('enlighten.run.index') }}" class="group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-white bg-gray-900 focus:outline-none focus:bg-gray-700 transition ease-in-out duration-150">
                            <svg class="mr-4 h-6 w-6 text-gray-300 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        @foreach($tabs as $tab)
                        <a href="{{ route('enlighten.run.show', ['run' => $activeRun->id, 'area' => $tab->slug]) }}" class="group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            {{ $tab->title }}
                        </a>
                        @endforeach
                    </nav>
                </div>
            </div>
            <div class="flex-shrink-0 w-14">
            </div>
        </div>
    </div>

    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64">
            <div class="flex flex-col h-0 flex-1">
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-900">
                    <span class="rounded-full text-teal-100 bg-teal-500 p-1">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    <span class="font-bold text-gray-100 text-2xl pl-4">Enlighten</span>
                </div>
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-2 py-4 bg-gray-800 space-y-1">
                        <a href="{{ route('enlighten.run.index') }}"
                           class="group flex items-center px-2 py-2 text-sm leading-5 font-medium text-white rounded-md focus:outline-none hover:bg-gray-700 focus:bg-gray-700 transition ease-in-out duration-150">
                            <svg class="mr-3 h-6 w-6 text-gray-300 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        @foreach($tabs as $tab)
                            <a href="{{ route('enlighten.run.show', ['run' => $activeRun->id, 'area' => $tab->slug]) }}"
                               class="group flex items-center px-2 py-2 text-sm leading-5 font-medium text-white rounded-md focus:outline-none transition ease-in-out duration-150 {{ request()->route('area') === $tab->slug ? 'bg-gray-900 hover:bg-gray-700' : 'focus:bg-gray-700 hover:bg-gray-600' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            {{ $tab->title }}
                        </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
            <button x-on:click="open = ! open" class="px-4 border-r border-gray-700 text-gray-500 focus:outline-none bg-gray-900 focus:bg-gray-800 focus:text-gray-600 md:hidden" aria-label="Open sidebar">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </button>
            <div class="flex-1 px-4 flex justify-end items-center bg-gray-800">
                @if($activeRun)
                    <div class="relative flex-1" x-data="{open: true}">
                        <input
                                x-on:input.debounce="fetch(`{{ route('enlighten.api.search', ['run' => $activeRun]) }}?search=${$event.target.value}`)
                                    .then(response => response.text())
                                    .then(html => { $refs.dropdown.innerHTML = html; open = true })"
                                class="bg-gray-900 w-full text-sm placeholder-gray-300 text-gray-300 rounded-md focus:outline-none focus:bg-gray-100 focus:text-gray-800 px-3 py-3"
                                placeholder="Search"
                                type="text"
                                role="search"
                                name="search"
                                value="{{request()->query('q')}}">
                        <div x-cloak x-ref="dropdown" x-show-="open" x-on:click.away="open = false"class="absolute block w-full my-1"></div>
                    </div>
                @endif
                <div class="ml-4 flex items-center md:ml-6">
                    @if($activeRun)
                        <div class="text-gray-200 font-light px-4 flex justify-center">
                            @if($activeRun->modified)
                                <span class="text-red-500 text-lg px-2">*</span>
                            @endif
                            {{ $runLabel }}
                        </div>
                    @endif
                    <a href="https://github.com/StydeNet/enlighten"
                       class="text-gray-100 hover:text-gray-500 focus:outline-none" aria-label="Notifications">
                        <svg class="h-8 w-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-4.466 19.59c-.405.078-.534-.171-.534-.384v-2.195c0-.747-.262-1.233-.55-1.481 1.782-.198 3.654-.875 3.654-3.947 0-.874-.312-1.588-.823-2.147.082-.202.356-1.016-.079-2.117 0 0-.671-.215-2.198.82-.64-.18-1.324-.267-2.004-.271-.68.003-1.364.091-2.003.269-1.528-1.035-2.2-.82-2.2-.82-.434 1.102-.16 1.915-.077 2.118-.512.56-.824 1.273-.824 2.147 0 3.064 1.867 3.751 3.645 3.954-.229.2-.436.552-.508 1.07-.457.204-1.614.557-2.328-.666 0 0-.423-.768-1.227-.825 0 0-.78-.01-.055.487 0 0 .525.246.889 1.17 0 0 .463 1.428 2.688.944v1.489c0 .211-.129.459-.528.385-3.18-1.057-5.472-4.056-5.472-7.59 0-4.419 3.582-8 8-8s8 3.581 8 8c0 3.533-2.289 6.531-5.466 7.59z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <main class="flex-1 relative overflow-y-auto focus:outline-none bg-gray-900" tabindex="0">
            <span id="top"></span>
            <div class="pt-2 pb-6">
                <div class="max-w-7xl mx-auto px-4">
                    <span class="block w-full border-b border-gray-300 py-4 mb-8">
                        <h1 class="text-3xl text-gray-100">{{ $title }}</h1>
                    </span>
                </div>
                <div class="max-w-7xl mx-auto px-4">
                    {!! $slot !!}
                </div>
            </div>
        </main>
    </div>
</div>
