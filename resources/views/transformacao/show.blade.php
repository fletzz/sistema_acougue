<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transformação #{{ $transformacao->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('transformacao.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>

            <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Origem</h3>
                <p><strong>Produto:</strong> {{ $transformacao->produtoOrigem->nome }}</p>
                <p><strong>Quantidade:</strong> {{ number_format($transformacao->quantidade_origem, 3, ',', '.') }} {{ $transformacao->produtoOrigem->unidade_medida }}</p>
                <p><strong>Data:</strong> {{ $transformacao->data_transformacao->format('d/m/Y H:i') }}</p>
                <p><strong>Usuário:</strong> {{ $transformacao->usuario->name }}</p>
                @if($transformacao->observacao)
                <p><strong>Observação:</strong> {{ $transformacao->observacao }}</p>
                @endif
            </div>

            <div style="background: white; padding: 20px; border-radius: 8px;">
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Produtos Destino</h3>
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f3f4f6;">
                            <th style="padding: 10px; text-align: left;">Produto</th>
                            <th style="padding: 10px; text-align: left;">Quantidade</th>
                            <th style="padding: 10px; text-align: left;">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transformacao->itens as $item)
                        <tr>
                            <td style="padding: 10px;">{{ $item->produtoDestino->nome }}</td>
                            <td style="padding: 10px;">{{ number_format($item->quantidade, 3, ',', '.') }} {{ $item->produtoDestino->unidade_medida }}</td>
                            <td style="padding: 10px;">
                                @if($item->tipo == 'corte')
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px;">Corte</span>
                                @else
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px;">Resto</span>
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

