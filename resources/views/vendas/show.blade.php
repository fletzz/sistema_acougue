<h1>Detalhes da Venda #{{ $venda->id }}</h1>

<a href="/vendas">Voltar para o Histórico</a>
<hr>

<h3>Resumo da Venda</h3>
<p>
    <strong>Data da Venda:</strong> 
    {{ $venda->data_venda->format('d/m/Y H:i') }}
</p>
<p>
    <strong>Cliente:</strong> 
    @if ($venda->cliente)
        {{ $venda->cliente->nome }}
    @else
        Consumidor Final
    @endif
</p>
<p>
    <strong>Vendedor:</strong> 
    {{ $venda->user->name }}
</p>
<p style="font-size: 20px; font-weight: bold;">
    <strong>Valor Total:</strong> 
    R$ {{ number_format($venda->valor_total_final, 2, ',', '.') }}
</p>

<hr>

<h3>Itens Vendidos</h3>
<table border="1" style="width: 800px;">
    <thead>
        <tr>
            <th>ID do Produto</th>
            <th>Produto</th>
            <th>Qtd.</th>
            <th>Preço Unit.</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($venda->items as $item)
            <tr>
                <td>{{ $item->produto_id }}</td>
                <td>{{ $item->produto->nome }}</td>
                <td>{{ $item->quantidade }}</td>
                <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                <td>
                    R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>