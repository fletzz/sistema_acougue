<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entrada #{{ $entrada->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <p><strong>Fornecedor:</strong> {{ $entrada->fornecedor->razao_social }}</p>
                <p><strong>Número:</strong> {{ $entrada->numero_nota }}</p>
                <p><strong>Valor Total:</strong> R$ {{ number_format($entrada->valor_total_nota, 2, ',', '.') }}</p>
            </div>

            <h3>Itens</h3>
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Custo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entrada->itens as $item)
                    <tr>
                        <td>{{ $item->produto->nome }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco_custo_unitario, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

