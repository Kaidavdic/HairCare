<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-outline inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-base-content uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>