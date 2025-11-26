@props(['icon', 'label', 'route'])

@php
    $active = request()->routeIs($route);

    $classes = $active
        ? 'bg-indigo-50 text-indigo-700 font-semibold'
        : 'text-gray-700 hover:bg-gray-100';
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 {{ $classes }}">
       
    <span class="w-5 h-5 {{ $active ? 'text-indigo-600' : 'text-gray-500' }}">
        @include('icons.' . $icon)
    </span>

    <span class="text-[15px]">
        {{ $label }}
    </span>
</a>
