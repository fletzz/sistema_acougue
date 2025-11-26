<div class="w-full bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8">

    <h1 class="text-lg font-semibold text-gray-800">
        {{ $title ?? 'Título padrão' }}
    </h1>

    {{-- Notificação opcional --}}
    <button class="text-gray-500 hover:text-gray-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160ZM480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Z"/></svg>
    </button>
</div>
