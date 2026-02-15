<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-primary-content uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>