<div class="w-full flex justify-center mx-auto mb-8 animate__animated animate__fadeIn">
	<div class="flex flex-wrap items-center justify-center gap-2 p-1.5 bg-base-content/5 rounded-2xl backdrop-blur-md">
		{{-- "All" Filter --}}
		<a href="{{ route('orders.index') }}" wire:navigate
			class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 select-none
           {{ !request()->has('status')
											    ? 'bg-red-600 text-white shadow-lg shadow-red-600/20'
											    : 'text-base-content/40 hover:bg-base-content/5 hover:text-base-content' }}">
			{{ __('Todos') }}
		</a>

		@foreach ($orderStatus as $status)
			<a href="{{ route('orders.index', ['status' => $status]) }}" wire:navigate
				class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 select-none
               {{ request('status') === $status
															    ? 'bg-red-600 text-white shadow-lg shadow-red-600/20'
															    : 'text-base-content/40 hover:bg-base-content/5 hover:text-base-content' }}">
				{{ __($status) }}
			</a>
		@endforeach
	</div>
</div>
