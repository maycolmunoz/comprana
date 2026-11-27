<section class="mb-10 flex flex-col items-center gap-6 md:flex-row">
    <div class="w-full max-w-md grow md:w-auto" x-data="{ query: @js(request('search', '')) }">
        <div class="group relative">
            <div class="text-base-content/50 pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 transition-colors group-focus-within:text-red-600">
                <x-mary-icon name="o-magnifying-glass" class="h-5 w-5" />
            </div>

            <input
                x-model="query"
                type="text"
                placeholder="¿Qué estás buscando hoy?"
                class="bg-base-100 border-base-content/10 w-full rounded-2xl py-4 pr-32 pl-12 font-normal shadow-sm transition-all outline-none group-hover:shadow-md focus:border-red-600 focus:ring-4 focus:ring-red-600/5"
                @keyup.enter="$dispatch('search', { value: query })"
            />

            <div class="absolute inset-y-2 right-2">
                <x-mary-button
                    label="Buscar"
                    class="btn-primary btn-sm shadow-primary/20 h-full rounded-xl px-6 font-bold shadow-lg"
                    x-on:click="$dispatch('search', { value: query })"
                />
            </div>
        </div>
    </div>

    <div class="group relative w-full md:w-64" x-data="{ open: false }" @click.away="open = false">
        <button
            @click="open = ! open"
            class="bg-base-100 border-base-content/10 hover:bg-base-200/50 group flex w-full items-center justify-between rounded-2xl border px-6 py-4 text-sm font-bold tracking-wide uppercase shadow-sm transition-all hover:border-red-600/30"
        >
            <div class="flex items-center gap-3">
                <div class="h-2 w-2 rounded-full bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.5)]"></div>
                <span class="truncate">{{ request('section', 'Todas las secciones') }}</span>
            </div>
            <x-mary-icon
                name="o-chevron-down"
                class="text-base-content/50 h-4 w-4 transition-transform duration-300"
                ::class="open ? 'rotate-180 text-red-600' : ''"
            />
        </button>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="bg-base-100/90 border-base-content/10 absolute right-0 left-0 z-50 mt-3 max-h-[60vh] overflow-y-auto rounded-3xl border p-2 shadow-2xl backdrop-blur-xl"
            style="display: none"
        >
            <div class="grid grid-cols-1 gap-1">
                <a
                    href="{{ route('products.index') }}"
                    wire:navigate
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ !request('section') ? 'bg-red-600 text-white shadow-md shadow-red-600/10' : 'hover:bg-base-content/5 text-base-content/60 hover:text-red-600' }}"
                >
                    <x-mary-icon name="o-squares-2x2" class="h-4 w-4" />
                    Ver todo
                </a>

                @foreach ($sections as $section)
                    <a
                        wire:navigate
                        href="{{ route('products.index', ['section' => $section->name]) }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ request('section') == $section->name ? 'bg-red-600 text-white shadow-md shadow-red-600/10' : 'hover:bg-base-content/5 text-base-content/60 hover:text-red-600' }}"
                    >
                        <div class="w-1.5 h-1.5 rounded-full {{ request('section') == $section->name ? 'bg-white' : 'bg-red-600/30 group-hover:bg-red-600' }} transition-colors"></div>
                        {{ $section->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
