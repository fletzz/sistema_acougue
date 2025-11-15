<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Lista de Produtos</h1>
    <div>
        <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
        <a href="{{ route('produtos.create') }}" style="padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Novo Produto</a>
    </div>
</div>

<table border="1" style="width: 800px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome do Produto</th>
            <th>Categoria</th>
            <th>Preço (R$)</th>
            <th>Un. Medida</th>
            <th>Estoque Atual</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produtos as $produto)
            <tr>
                <td>{{ $produto->id }}</td>
                <td>{{ $produto->nome }}</td>
                <td>{{ $produto->categoria->nome }}</td>
                <td>R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                <td>{{ $produto->unidade_medida }}</td>
                <td>{{ number_format($produto->estoque_atual, 3, ',', '.') }} {{ $produto->unidade_medida }}</td>
                <td>
                    <a href="/produtos/{{ $produto->id }}/edit">
                        Editar
                    </a>
                    
                    <form action="/produtos/{{ $produto->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>