<div x-cloak x-data="{ open: false, timeout: null, message: '', type: 'success' }"
    x-on:notification.window=" 
        open = true;
        clearTimeout(timeout);
        message = $event.detail.message || $event.detail;
        type = $event.detail.type || 'success';
        timeout = setTimeout(() => { open = false; }, 5000);
    "
    x-show="open" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed z-100 bottom-5 right-5 w-full max-w-sm px-4">

    <div
        class="bg-base-100 border border-base-content/10 shadow-2xl rounded-3xl p-4 flex items-center gap-4 backdrop-blur-xl">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0"
                :class="{
                    'bg-success/10 text-success': type === 'success',
                    'bg-error/10 text-error': type === 'error',
                    'bg-warning/10 text-warning': type === 'warning'
                }">
            <x-mary-icon name="o-check-circle" class="w-6 h-6" x-show="type === 'success'" />
            <x-mary-icon name="o-x-circle" class="w-6 h-6" x-show="type === 'error'" />
            <x-mary-icon name="o-exclamation-triangle" class="w-6 h-6" x-show="type === 'warning'" />
        </div>
        <div class="grow">
            <p class="text-sm font-bold leading-tight" x-text="message"></p>
        </div>
        <button @click="open = false" class="opacity-30 hover:opacity-100 transition-opacity">
            <x-mary-icon name="o-x-mark" class="w-4 h-4" />
        </button>
    </div>
</div>
