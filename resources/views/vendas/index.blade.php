<h1>Histórico de Vendas</h1>

<a href="/vendas/create">Ir para a Frente de Caixa (PDV)</a>

<hr>

<table border="1" style="width: 100%;">
    <thead>
        <tr>
            <th>ID da Venda</th>
            <th>Data</th>
            <th>Cliente</th>
            <th>Vendedor (Usuário)</th>
            <th>Status</th>
            <th>Valor Total</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendas as $venda)
            <tr>
                <td>{{ $venda->id }}</td>
                <td>
                    {{ $venda->data_venda->format('d/m/Y H:i') }}
                </td>
                
                <td>
                    @if ($venda->cliente)
                        {{ $venda->cliente->nome }}
                    @else
                        Consumidor Final
                    @endif
                </td>

                <td>{{ $venda->user->name }}</td>

                <td>{{ $venda->status }}</td>
                <td>R$ {{ number_format($venda->valor_total_final, 2, ',', '.') }}</td>
                
                <td>
                    <a href="/vendas/{{ $venda->id }}">
                        Ver Detalhes
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>