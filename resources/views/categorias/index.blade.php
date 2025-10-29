<h1>Lista de Categorias</h1>

<table border="1" style="width: 300px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categorias as $categoria)
            <tr>
                <td>{{ $categoria->id }}</td>
                <td>{{ $categoria->nome }}</td>
            </tr>
        @endforeach
    </tbody>
</table>