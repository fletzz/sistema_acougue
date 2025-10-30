<h1>Cadastrar Nova Categoria</h1>

<form action="/categorias" method="POST">
    @csrf

    <div>
        <label for="nome">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome">
    </div>

    <button type="submit">Salvar</button>
</form>

<br>
<a href="/categorias">Voltar para a lista</a>