<div class="space-y-2 text-sm text-gray-700">
    <div class="grid grid-cols-4 font-semibold text-xs text-gray-400 pb-1 border-b border-gray-100">
        <span>Data</span>
        <span>Cliente</span>
        <span>Total</span>
        <span class="text-right">Status</span>
    </div>

    @forelse ($vendasRecentes as $venda)
        <div class="grid grid-cols-4 items-center py-1.5 border-b border-gray-50">
            <span>
                {{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}
            </span>
            <span>{{ $venda->cliente->nome ?? 'Balcão' }}</span>
            <span>R$ {{ number_format($venda->valor_total_final, 2, ',', '.') }}</span>
            <span class="text-right">
                {{ ucfirst($venda->status ?? 'finalizada') }}
            </span>
        </div>
    @empty
        <p class="text-xs text-gray-400 mt-2">Nenhuma venda finalizada encontrada.</p>
    @endforelse
</div>
