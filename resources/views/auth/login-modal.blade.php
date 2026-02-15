<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="haircare">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uspešna prijava - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-base-100">
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" id="loginModal">
        <div class="bg-base-100 rounded-lg shadow-lg max-w-md w-full mx-4 p-8">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-success animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <h2 class="text-2xl font-bold text-base-content mb-2">Uspešna prijava!</h2>
                <p class="text-base-content/70 mb-6">Dobrodošli! Vraćamo vas na prethodnu stranicu...</p>
                <div class="flex flex-col gap-3">
                    <div class="loading loading-spinner loading-lg mx-auto"></div>
                    <p class="text-sm text-base-content/50">Preusmeravamo vas za nekoliko sekundi...</p>
                    <a href="{{ $redirect }}" class="btn btn-primary">
                        Nazad na stranicu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Redirect after 2 seconds
        setTimeout(function () {
            window.location.href = "{{ $redirect }}";
        }, 2000);
    </script>
</body>

</html>