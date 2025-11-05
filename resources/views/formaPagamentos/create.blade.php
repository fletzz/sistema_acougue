<h1>Cadastrar Nova Forma de Pagamento</h1>

<form action="/forma_pagamentos" method="POST">
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
        <label for="descricao">Descrição (ex: Dinheiro, Cartão de Crédito):</label>
        <input type="text" id="descricao" name="descricao" style="width: 300px;">
    </div>

    <div>
        <label for="tipo">Tipo (Opcional, ex: Cartão, Dinheiro, Pix):</label>
        <input type="text" id="tipo" name="tipo" style="width: 300px;">
    </div>

    <button type="submit">Salvar Forma de Pagamento</button>
</form>

<br>
<a href="/forma_pagamentos">Voltar para a lista</a>