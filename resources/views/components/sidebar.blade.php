<aside
    x-data="{ open: true }"
    class="bg-white border-r border-gray-200 h-screen flex flex-col transition-all duration-300 w-[270px]"
>
    
    <div class="flex items-center justify-between px-4 h-20 border-b border-gray-100">

        {{-- LOGO CENTRALIZADA --}}
        <div class="flex justify-center w-full" :class="open ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
            <img src="{{ asset('images/logo-flamboyant.svg') }}" class="h-12 mx-auto" alt="">
        </div>
    </div>


    {{-- Conteúdo --}}
    <nav class="flex-1 px-4 pt-6 text-sm space-y-7">

        {{-- Menu principal --}}
        <div>
            <p class="text-[11px] font-semibold tracking-[0.20em] text-gray-400 uppercase mb-3"
               :class="open ? 'opacity-100' : 'opacity-0'">
               Menu Principal
            </p>

            <div class="space-y-1">
                <x-sidebar-item icon="dashboard" label="Dashboard" route="dashboard"/>
                <x-sidebar-item icon="checkout" label="Checkout" route="checkout"/>
                <x-sidebar-item icon="estoque" label="Estoque" route="produtos.index"/>
                <x-sidebar-item icon="entrada" label="Entrada" route="entrada_mercadoria.index"/>
                <x-sidebar-item icon="clientes" label="Clientes" route="clientes.index"/>
                <x-sidebar-item icon="relatorios" label="Relatórios" route="relatorios.vendas"/>
            </div>
        </div>

        {{-- Configurações --}}
        <div>
            <p class="text-[11px] font-semibold tracking-[0.20em] text-gray-400 uppercase mb-3"
               :class="open ? 'opacity-100' : 'opacity-0'">
               Configurações
            </p>

            <div class="space-y-1">
                <x-sidebar-item icon="config" label="Emitente" route="emitente.index"/>
                <x-sidebar-item icon="perfil" label="Perfil" route="profile.edit"/>
            </div>
        </div>

    </nav>

    {{-- USER FOOTER --}}
<div class="mt-auto p-4 border-t border-gray-200">

    <div class="flex items-center gap-3"
         :class="open ? 'flex' : 'hidden'">

        {{-- Avatar simples --}}
        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-semibold">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>

        <div class="leading-tight">
            <p class="text-[15px] font-semibold text-gray-800">
                {{ Auth::user()->name }}
            </p>
            <p class="text-[13px] text-gray-500">
                {{ Auth::user()->role ?? 'Admin' }}
            </p>
        </div>

    </div>

    {{-- Botão sair --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button
            class="w-full flex items-center gap-3 text-gray-600 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded-md transition text-sm"
            :class="open ? 'justify-start' : 'justify-center'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>

            <span x-show="open" class="text-[15px]">
                Sair
            </span>
        </button>
    </form>

</div>


</aside>
