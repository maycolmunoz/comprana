@props([
    'title' => '',
    'list' => [],
])

<div class="bg-base-100 border-base-content/5 mx-auto my-8 max-w-2xl overflow-hidden rounded-3xl border shadow-xl shadow-red-900/5">
    <details class="group">
        <summary class="flex cursor-pointer list-none items-center justify-between px-6 py-4 font-bold transition-colors outline-none">
            <div class="flex items-center gap-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-red-600/10 text-red-600 transition-all duration-300 group-open:bg-red-600 group-open:text-white">
                    <x-mary-icon name="o-information-circle" class="h-5 w-5" />
                </div>
                <span class="text-base-content/80 text-sm tracking-widest uppercase transition-colors group-open:text-red-600">{{ $title }}</span>
            </div>

            <x-mary-icon
                name="o-chevron-right"
                class="text-base-content/50 h-4 w-4 transition-transform duration-300 group-open:rotate-90"
            />
        </summary>

        <article class="px-8 pb-6">
            <div class="via-base-content/5 mb-6 h-px w-full bg-linear-to-r from-transparent to-transparent"></div>

            <ul class="space-y-4">
                @foreach ($list as $li)
                    <li class="flex items-start gap-4">
                        <x-mary-icon name="o-check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-red-600" />
                        <span class="text-base-content/70 text-xs leading-relaxed">{{ $li }}</span>
                    </li>
                @endforeach
            </ul>
        </article>
    </details>
</div>
