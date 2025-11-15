<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entrada #{{ $entrada->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('entrada_mercadoria.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>

            <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Informações</h3>
                <p><strong>Fornecedor:</strong> {{ $entrada->fornecedor->razao_social ?? 'Sem fornecedor' }}</p>
                <p><strong>Data:</strong> {{ $entrada->data_entrada->format('d/m/Y H:i') }}</p>
                <p><strong>Usuário:</strong> {{ $entrada->usuario->name }}</p>
                @if($entrada->observacao)
                <p><strong>Observação:</strong> {{ $entrada->observacao }}</p>
                @endif
            </div>

            <div style="background: white; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Produtos</h3>
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f3f4f6;">
                            <th style="padding: 10px; text-align: left;">Produto</th>
                            <th style="padding: 10px; text-align: left;">Quantidade</th>
                            <th style="padding: 10px; text-align: left;">Preço Custo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrada->itens as $item)
                        <tr>
                            <td style="padding: 10px;">{{ $item->produto->nome }}</td>
                            <td style="padding: 10px;">{{ number_format($item->quantidade, 3, ',', '.') }} {{ $item->produto->unidade_medida }}</td>
                            <td style="padding: 10px;">
                                @if($item->preco_custo)
                                R$ {{ number_format($item->preco_custo, 2, ',', '.') }}
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

