<a href="/categorias/create">Cadastrar Nova Categoria</a>

<h1>Lista de Categorias</h1>

<table border="1" style="width: 400px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categorias as $categoria)
            <tr>
                <td>{{ $categoria->id }}</td>
                <td>{{ $categoria->nome }}</td>
                <td>
                    <a href="/categorias/{{ $categoria->id }}/edit">
                        Editar
                    </a>
                    
                    <form action="/categorias/{{ $categoria->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>