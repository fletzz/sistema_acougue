<h1>Editar Fornecedor: {{ $fornecedor->razao_social }}</h1>

<form action="/fornecedores/{{ $fornecedor->id }}" method="POST">
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
        <label for="razao_social">Razão Social:</label>
        <input type="text" id="razao_social" name="razao_social" style="width: 300px;" value="{{ $fornecedor->razao_social }}">
    </div>

    <div>
        <label for="nome_fantasia">Nome Fantasia (Opcional):</label>
        <input type="text" id="nome_fantasia" name="nome_fantasia" style="width: 300px;" value="{{ $fornecedor->nome_fantasia }}">
    </div>

    <div>
        <label for="cnpj">CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj" value="{{ $fornecedor->cnpj }}">
    </div>

    <div>
        <label for="telefone">Telefone (Opcional):</label>
        <input type="text" id="telefone" name="telefone" value="{{ $fornecedor->telefone }}">
    </div>

    <div>
        <label for="email">Email (Opcional):</label>
        <input type="email" id="email" name="email" value="{{ $fornecedor->email }}">
    </div>

    <div>
        <label for="endereco">Endereço (Opcional):</label>
        <textarea id="endereco" name="endereco" style="width: 300px;">{{ $fornecedor->endereco }}</textarea>
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="/fornecedores">Voltar para a lista</a>