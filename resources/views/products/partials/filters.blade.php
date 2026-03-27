<section class="flex flex-col md:flex-row items-center gap-6 mb-10 animate__animated animate__fadeIn">
	{{-- -----Search Box---- --}}
	<div class="w-full md:w-auto grow max-w-md" x-data="{ query: '{{ request('search' ?? '') }}' }">
		<div class="relative group">
			<div
				class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-base-content/30 group-focus-within:text-red-600 transition-colors">
				<x-mary-icon name="o-magnifying-glass" class="w-5 h-5" />
			</div>

			<input x-model="query" type="text" placeholder="¿Qué estás buscando hoy?"
				class="w-full bg-base-100 border-base-content/10 focus:border-red-600 focus:ring-4 focus:ring-red-600/5 rounded-2xl py-4 pl-12 pr-32 font-medium transition-all shadow-sm group-hover:shadow-md outline-none"
				@keyup.enter="$dispatch('search', {value: query})" />

			<div class="absolute inset-y-2 right-2">
				<x-mary-button label="Buscar"
					class="btn-primary btn-sm h-full px-6 font-bold rounded-xl shadow-lg shadow-primary/20"
					x-on:click="$dispatch('search', {value: query})" />
			</div>
		</div>
	</div>

	{{-- ---Section Selector--- --}}
	<div class="relative w-full md:w-64 group" x-data="{ open: false }" @click.away="open = false">
		{{-- Trigger Button --}}
		<button @click="open = !open"
			class="w-full flex items-center justify-between px-6 py-4 bg-base-100 border border-base-content/10 rounded-2xl font-bold text-sm tracking-wide uppercase hover:bg-base-200/50 hover:border-red-600/30 transition-all shadow-sm group">
			<div class="flex items-center gap-3">
				<div class="w-2 h-2 rounded-full bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.5)]"></div>
				<span class="truncate">{{ request('section', 'Todas las Secciones') }}</span>
			</div>
			<x-mary-icon name="o-chevron-down" class="w-4 h-4 text-base-content/30 transition-transform duration-300"
				::class="open ? 'rotate-180 text-red-600' : ''" />
		</button>

		{{-- Dropdown Options --}}
		<div x-show="open" x-transition:enter="transition ease-out duration-200"
			x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
			x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
			x-transition:leave-end="opacity-0 translate-y-4"
			class="absolute left-0 right-0 mt-3 p-2 bg-base-100/90 backdrop-blur-xl border border-white/50 shadow-2xl rounded-3xl z-50 max-h-[60vh] overflow-y-auto"
			style="display: none;">
			<div class="grid grid-cols-1 gap-1">
				<a href="{{ route('products.index') }}"
					class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ !request('section') ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'hover:bg-red-50 text-base-content/60 hover:text-red-700' }}">
					<x-mary-icon name="o-squares-2x2" class="w-4 h-4" />
					Ver Todo
				</a>

				@foreach ($sections as $section)
					<a wire:navigation href="{{ route('products.index', ['section' => $section->name]) }}"
						class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ request('section') == $section->name ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'hover:bg-red-50 text-base-content/60 hover:text-red-700' }}">
						<div
							class="w-1.5 h-1.5 rounded-full {{ request('section') == $section->name ? 'bg-white' : 'bg-red-600/30 group-hover:bg-red-600' }} transition-colors">
						</div>
						{{ $section->name }}
					</a>
				@endforeach
			</div>
		</div>
	</div>
</section>
