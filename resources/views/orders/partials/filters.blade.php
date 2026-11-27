<div class="mx-auto mb-8 flex w-full justify-center">
    <div class="bg-base-content/5 flex flex-wrap items-center justify-center gap-2 rounded-2xl p-1.5 backdrop-blur-md">
        <a
            href="{{ route('orders.index') }}"
            wire:navigate
            class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 select-none
                {{
                    ! request()->has('status')
                    ? 'bg-red-600 text-white shadow-md shadow-red-600/10'
                    : 'text-base-content/40 hover:bg-base-content/5 hover:text-base-content'
                }}"
        >
            Todos
        </a>

        @foreach ($orderStatuses as $status)
            <a
                href="{{ route('orders.index', ['status' => $status->value]) }}"
                wire:navigate
                class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 select-none
                    {{
                        request('status') === $status->value
                        ? 'bg-red-600 text-white shadow-md shadow-red-600/10'
                        : 'text-base-content/40 hover:bg-base-content/5 hover:text-base-content'
                    }}"
            >
                {{ $status->getLabel() }}
            </a>
        @endforeach
    </div>
</div>
