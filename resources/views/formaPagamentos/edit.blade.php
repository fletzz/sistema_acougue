<h1>Editar Forma de Pagamento: {{ $formaPagamento->descricao }}</h1>

<form action="/forma_pagamentos/{{ $formaPagamento->id }}" method="POST">
    @csrf
    @method('PUT') @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
            <strong>Ops! Algo deu errado:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" style="width: 300px;" value="{{ $formaPagamento->descricao }}">
    </div>

    <div>
        <label for="tipo">Tipo (Opcional):</label>
        <input type="text" id="tipo" name="tipo" style="width: 300px;" value="{{ $formaPagamento->tipo }}">
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="/forma_pagamentos">Voltar para a lista</a>