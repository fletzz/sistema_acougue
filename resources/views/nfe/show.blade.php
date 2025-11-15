<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            NFe #{{ $notaFiscal->numero }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('nfe.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar para NFe</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>
            
            <div>
                <p><strong>Status:</strong> {{ $notaFiscal->status }}</p>
                <p><strong>Chave de Acesso:</strong> {{ $notaFiscal->chave_acesso }}</p>
                <p><strong>Valor Total:</strong> R$ {{ number_format($notaFiscal->valor_total_nfe, 2, ',', '.') }}</p>
            </div>

            <h3>Itens</h3>
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notaFiscal->itens as $item)
                    <tr>
                        <td>{{ $item->descricao }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('nfe.xml', $notaFiscal->id) }}">Download XML</a>
        </div>
    </div>
</x-app-layout>

