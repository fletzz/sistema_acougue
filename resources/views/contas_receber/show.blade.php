<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Conta a Receber #{{ $contaReceber->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <p><strong>Venda:</strong> #{{ $contaReceber->venda_id }}</p>
                <p><strong>Cliente:</strong> {{ $contaReceber->cliente->nome ?? 'N/A' }}</p>
                <p><strong>Valor:</strong> R$ {{ number_format($contaReceber->valor_pago, 2, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ $contaReceber->status ?? 'pendente' }}</p>
            </div>
        </div>
    </div>
</x-app-layout>

