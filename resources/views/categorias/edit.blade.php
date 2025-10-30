<h1>Editar Categoria</h1>

<form action="/categorias/{{ $categoria->id }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="nome">Nome da Categoria:</label>
        <input type="text" id="nome" name="nome" value="{{ $categoria->nome }}">
    </div>

    <button type="submit">Salvar</button>
</form>

<br>
<a href="/categorias">Voltar para a lista</a>