<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Histórico de Vendas</h1>
    <div>
        <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
        <a href="{{ route('vendas.create') }}" style="padding: 10px 20px; background: #059669; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Nova Venda</a>
    </div>
</div>

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