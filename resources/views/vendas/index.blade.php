<x-app-layout>
    <x-slot name="title">Caixa</x-slot>

    <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center text-center">

        @if(!$caixa)
            <h2 class="text-3xl font-bold mb-2">Caixa fechado</h2>
            <p class="text-gray-500">Abra o caixa para iniciar as vendas.</p>

            <form method="POST" action="{{ route('checkout.abrir') }}">
                @csrf
                <button class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Abrir caixa
                </button>
            </form>

        @else
            <h2 class="text-3xl font-bold mb-2">Caixa aberto</h2>
            <p class="text-gray-500">{{ $estabelecimentoNome ?? 'Flamboyant Market' }}</p>

            <div class="mt-6 bg-white shadow p-6 rounded w-80">
                <p class="font-semibold">Saldo inicial: 
                    R$ {{ number_format($caixa->saldo_inicial,2,',','.') }}
                </p>
                <p class="font-semibold mt-2">Aberto em: 
                    {{ $caixa->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <a href="{{ route('checkout.pdv') }}"
               class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Iniciar PDV
            </a>
        @endif

    </div>
</x-app-layout>
