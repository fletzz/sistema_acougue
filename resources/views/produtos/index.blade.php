<a href="/produtos/create">Cadastrar Novo Produto</a>

<h1>Lista de Produtos</h1>

<table border="1" style="width: 800px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome do Produto</th>
            <th>Categoria</th> <th>Preço (R$)</th>
            <th>Un. Medida</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produtos as $produto)
            <tr>
                <td>{{ $produto->id }}</td>
                <td>{{ $produto->nome }}</td>
                
                <td>{{ $produto->categoria->nome }}</td>
                
                <td>{{ $produto->preco_venda }}</td>
                <td>{{ $produto->unidade_medida }}</td>
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