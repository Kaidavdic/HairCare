@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary text-start text-base font-medium text-base-content bg-base-200 focus:outline-none focus:text-base-content focus:bg-base-200 focus:border-primary transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-base-content/80 hover:text-base-content hover:bg-base-200 hover:border-base-200 focus:outline-none focus:text-base-content focus:bg-base-200 focus:border-base-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>