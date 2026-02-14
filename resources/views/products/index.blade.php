<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center gap-3">
			<div class="w-1.5 h-8 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.4)]"></div>
			<h2 class="text-3xl font-black tracking-tighter uppercase text-base-content">
				{{ __('Nuestra Tienda') }}
			</h2>
		</div>
	</x-slot>

	{{-- Product Details Drawer (Slide-over) --}}
	<div x-data="{ slideOverOpen: false }" @slider.window="slideOverOpen = true" @slider-close.window="slideOverOpen = false"
		class="relative z-[100]">

		<template x-teleport="body">
			<div x-show="slideOverOpen" @keydown.window.escape="slideOverOpen = false" class="relative z-[100]" x-cloak>

				{{-- Backdrop --}}
				<div x-show="slideOverOpen" x-transition:enter="transition-opacity ease-out duration-500"
					x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
					x-transition:leave="transition-opacity ease-in duration-400" x-transition:leave-start="opacity-100"
					x-transition:leave-end="opacity-0" @click="slideOverOpen = false"
					class="fixed inset-0 bg-base-300/40 backdrop-blur-md"></div>

				<div class="fixed inset-0 overflow-hidden pointer-events-none">
					<div class="absolute inset-0 overflow-hidden">
						<div class="fixed inset-y-0 right-0 flex max-w-full pl-0 sm:pl-10">

							{{-- Panel --}}
							<div x-show="slideOverOpen" @click.away="slideOverOpen = false"
								x-transition:enter="transform transition ease-out duration-500 sm:duration-700"
								x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
								x-transition:leave="transform transition ease-in duration-400 sm:duration-700"
								x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
								class="w-screen max-w-2xl pointer-events-auto">

								<div
									class="flex flex-col h-full bg-base-100 border-l border-base-content/5 shadow-[-20px_0_50px_-15px_rgba(0,0,0,0.1)] relative overflow-hidden">
									{{-- Accent Top Line --}}
									<div class="h-1.5 w-full bg-linear-to-r from-red-600 to-red-400"></div>

									{{-- Header --}}
									<div
										class="px-6 py-8 sm:px-10 border-b border-base-content/5 flex items-center justify-between bg-base-100/50 backdrop-blur-sm sticky top-0 z-20">
										<div>
											<h2 class="text-2xl font-black tracking-tighter uppercase flex items-center gap-3">
												<x-mary-icon name="o-magnifying-glass-plus" class="w-6 h-6 text-red-600" />
												{{ __('Detalles del Producto') }}
											</h2>
											<p class="text-[10px] uppercase font-bold tracking-widest text-base-content/40 mt-1">
												Comprana Premium Experience
											</p>
										</div>

										<x-mary-button icon="o-x-mark" @click="slideOverOpen = false"
											class="btn-ghost btn-circle btn-sm hover:rotate-90 transition-transform duration-300" />
									</div>

									{{-- Content area --}}
									<div class="relative flex-1 overflow-y-auto px-6 py-8 sm:px-10 bg-base-200/30">
										<div class="animate__animated animate__fadeIn animate__delay-1s">
											<livewire:product-details />
										</div>
									</div>

									{{-- Bottom Decoration --}}
									<div class="px-6 py-4 border-t border-base-content/5 bg-base-100/80 backdrop-blur-md text-center">
										<div class="inline-flex items-center gap-2 opacity-20">
											<x-ui.brand :logo="asset('srcs/favicon.ico')" class="h-4 w-auto grayscale" />
											<span class="text-[8px] font-bold uppercase tracking-[.4em]">COMPRANA</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</template>
	</div>

	{{-- Filters & Search Section --}}
	<section class="bg-base-100 border-b border-base-content/5 mb-12">
		<div class="max-w-7xl mx-auto px-6 py-10 lg:py-16">
			<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
				<div class="max-w-xl animate__animated animate__fadeInLeft">
					<span class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-2 block">
						Catálogo Selecto
					</span>
					<h1 class="text-4xl lg:text-6xl font-black tracking-tighter uppercase leading-none mb-6">
						Explora Nuestras <span class="italic text-base-content/40">Colecciones</span>
					</h1>
					<p class="text-base text-base-content/50 font-medium">
						Calidad garantizada y entregas seguras. Encuentra lo que necesitas con la mejor experiencia de compra.
					</p>
				</div>

				<div class="animate__animated animate__fadeInRight">
					@include('products.partials.filters')
				</div>
			</div>
		</div>
	</section>

	{{-- Products Grid --}}
	<div class="max-w-7xl mx-auto px-6 pb-24 min-h-[60vh]">
		<livewire:product-list lazy />
	</div>
</x-app-layout>
