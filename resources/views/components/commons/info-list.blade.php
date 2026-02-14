@props([
    'title' => '',
    'list' => [],
])

<div
	class="max-w-2xl mx-auto my-8 overflow-hidden bg-base-100 border border-base-content/5 shadow-xl shadow-red-900/5 rounded-3xl animate__animated animate__fadeIn">
	<details class="group">
		{{-- button open --}}
		<summary
			class="flex items-center justify-between px-6 py-4 font-bold list-none transition-colors cursor-pointer outline-none">
			<div class="flex items-center gap-4">
				<div
					class="w-8 h-8 rounded-xl bg-red-600/10 flex items-center justify-center text-red-600 group-open:bg-red-600 group-open:text-white transition-all duration-300">
					<x-mary-icon name="o-information-circle" class="w-5 h-5" />
				</div>
				<span
					class="text-sm uppercase tracking-widest text-base-content/80 group-open:text-red-700 transition-colors">{{ $title }}</span>
			</div>

			<x-mary-icon name="o-chevron-right"
				class="w-4 h-4 text-base-content/30 transition-transform duration-300 group-open:rotate-90" />
		</summary>

		{{-- info --}}
		<article class="px-8 pb-6 animate__animated animate__fadeInDown animate__faster">
			<div class="h-px w-full bg-linear-to-r from-transparent via-base-content/5 to-transparent mb-6"></div>

			<ul class="space-y-4">
				@foreach ($list as $li)
					<li class="flex items-start gap-4 group/item">
						<x-mary-icon name="o-check" class="w-3 h-3" :label="$li" />
					</li>
				@endforeach
			</ul>
		</article>
	</details>
</div>
