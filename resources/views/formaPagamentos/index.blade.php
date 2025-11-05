<a href="/forma_pagamentos/create">Cadastrar Nova Forma de Pagamento</a>

<h1>Lista de Formas de Pagamento</h1>

<table border="1" style="width: 800px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Tipo (ex: Dinheiro, Cartão)</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($formaPagamentos as $formaPagamento)
            <tr>
                <td>{{ $formaPagamento->id }}</td>
                <td>{{ $formaPagamento->descricao }}</td>
                <td>{{ $formaPagamento->tipo }}</td>
                <td>
                    <a href="/forma_pagamentos/{{ $formaPagamento->id }}/edit">
                        Editar
                    </a>
                    
                    <form action="/forma_pagamentos/{{ $formaPagamento->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>