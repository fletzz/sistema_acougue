<h1>Cadastrar Novo Cliente</h1>

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

<form action="/clientes" method="POST">
    @csrf

    <div>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
    </div>

    <div>
        <label for="cpf_cnpj">CPF/CNPJ:</label>
        <input type="text" id="cpf_cnpj" name="cpf_cnpj">
    </div>

    <div>
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone">
    </div>

    <div>
        <label for="email">Email (Opcional):</label>
        <input type="email" id="email" name="email">
    </div>

    <button type="submit">Salvar Cliente</button>
</form>

<br>
<a href="/clientes">Voltar para a lista</a>