<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary px-4 py-2 rounded-2xl font-bold text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
