<h1>Editar Cliente: {{ $cliente->nome }}</h1>

<form action="/clientes/{{ $cliente->id }}" method="POST">
    @csrf
    @method('PUT') <div>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="{{ $cliente->nome }}">
    </div>

    <div>
        <label for="cpf_cnpj">CPF/CNPJ:</label>
        <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ $cliente->cpf_cnpj }}">
    </div>

    <div>
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="{{ $cliente->telefone }}">
    </div>

    <div>
        <label for="email">Email (Opcional):</label>
        <input type="email" id="email" name="email" value="{{ $cliente->email }}">
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="/clientes">Voltar para a lista</a>