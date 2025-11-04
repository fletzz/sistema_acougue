<a href="/usuarios/create">Cadastrar Novo Usuário</a>

<h1>Lista de Usuários</h1>

<table border="1" style="width: 1000px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Login</th>
            <th>Nível de Acesso</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->nome }}</td>
                <td>{{ $usuario->login }}</td>
                <td>{{ $usuario->nivel_acesso }}</td>
                <td>
                    <a href="/usuarios/{{ $usuario->id }}/edit">
                        Editar
                    </a>
                    
                    <form action="/usuarios/{{ $usuario->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>