<a href="/fornecedores/create">Cadastrar Novo Fornecedor</a>

<h1>Lista de Fornecedores</h1>

<table border="1" style="width: 1000px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Razão Social</th>
            <th>CNPJ</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fornecedores as $fornecedor)
            <tr>
                <td>{{ $fornecedor->id }}</td>
                <td>{{ $fornecedor->razao_social }}</td>
                <td>{{ $fornecedor->cnpj }}</td>
                <td>{{ $fornecedor->telefone }}</td>
                <td>{{ $fornecedor->email }}</td>
                <td>
                    <a href="/fornecedores/{{ $fornecedor->id }}/edit">
                        Editar
                    </a>
                    
                    <form action="/fornecedores/{{ $fornecedor->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>