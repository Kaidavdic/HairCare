<footer class="footer footer-center p-4 bg-base-200 text-base-content border-t border-base-300">
    <aside>
        <p>
            © {{ date('Y') }} {{ config('app.name', 'HairCare') }} - {{ __('Sva prava zadržana') }}
            | 
            <a href="{{ route('contact') }}" class="link link-hover">{{ __('Kontakt') }}</a>
        </p>
    </aside>
</footer>

