<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Movimentações - {{ $caixa->descricao }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('caixa.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar para Caixas</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>
            
            <p>Saldo Atual: R$ {{ number_format($caixa->saldo_atual, 2, ',', '.') }}</p>
            <p>Status do Caixa: {{ $caixa->status }}</p>
            <p>ID do Caixa: {{ $caixa->id }}</p>
            <p>Total de Movimentações: {{ $movimentacoes->count() }}</p>
            
            @if($movimentacoes->count() == 0)
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 5px;">
                <p><strong>Nenhuma movimentação encontrada para este caixa.</strong></p>
                <p>Possíveis causas:</p>
                <ul style="margin-left: 20px;">
                    <li>Nenhuma venda foi realizada ainda</li>
                    <li>Nenhuma movimentação manual foi criada</li>
                    <li>O caixa precisa estar aberto para registrar movimentações</li>
                </ul>
                <p style="margin-top: 10px;">
                    <strong>Para testar:</strong> Crie uma movimentação manual usando o formulário acima (se o caixa estiver aberto) ou faça uma venda.
                </p>
            </div>
            @endif
            
            @if($caixa->status == 'aberto')
            <form method="POST" action="{{ route('caixa.movimentacao', $caixa->id) }}" class="mb-4">
                @csrf
                <div>
                    <label>Tipo</label>
                    <select name="tipo_movimentacao" required>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                        <option value="suprimento">Suprimento</option>
                        <option value="sangria">Sangria</option>
                    </select>
                </div>
                <div>
                    <label>Valor</label>
                    <input type="number" name="valor" step="0.01" min="0.01" required>
                </div>
                <div>
                    <label>Observação</label>
                    <textarea name="observacao"></textarea>
                </div>
                <button type="submit">Adicionar Movimentação</button>
            </form>
            @endif

            @if($movimentacoes->count() > 0)
            <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 10px; text-align: left;">Data</th>
                        <th style="padding: 10px; text-align: left;">Tipo</th>
                        <th style="padding: 10px; text-align: left;">Valor</th>
                        <th style="padding: 10px; text-align: left;">Usuário</th>
                        <th style="padding: 10px; text-align: left;">Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimentacoes as $mov)
                    <tr>
                        <td style="padding: 10px;">{{ $mov->data_movimentacao->format('d/m/Y H:i') }}</td>
                        <td style="padding: 10px;">{{ $mov->tipo_movimentacao }}</td>
                        <td style="padding: 10px;">R$ {{ number_format($mov->valor, 2, ',', '.') }}</td>
                        <td style="padding: 10px;">{{ $mov->usuario->name ?? 'N/A' }}</td>
                        <td style="padding: 10px;">{{ $mov->observacao ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="margin-top: 20px; padding: 10px; background-color: #fef3c7; border: 1px solid #fbbf24;">Nenhuma movimentação registrada ainda.</p>
            @endif
        </div>
    </div>
</x-app-layout>

