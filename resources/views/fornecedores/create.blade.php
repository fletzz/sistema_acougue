<h1>Cadastrar Novo Fornecedor</h1>

<form action="/fornecedores" method="POST">
    @csrf

    @if ($errors->any())
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
        <input type="text" id="razao_social" name="razao_social" style="width: 300px;">
    </div>

    <div>
        <label for="nome_fantasia">Nome Fantasia (Opcional):</label>
        <input type="text" id="nome_fantasia" name="nome_fantasia" style="width: 300px;">
    </div>

    <div>
        <label for="cnpj">CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj">
    </div>

    <div>
        <label for="telefone">Telefone (Opcional):</label>
        <input type="text" id="telefone" name="telefone">
    </div>

    <div>
        <label for="email">Email (Opcional):</label>
        <input type="email" id="email" name="email">
    </div>

    <div>
        <label for="endereco">Endereço (Opcional):</label>
        <textarea id="endereco" name="endereco" style="width: 300px;"></textarea>
    </div>

    <button type="submit">Salvar Fornecedor</button>
</form>

<br>
<a href="/fornecedores">Voltar para a lista</a>