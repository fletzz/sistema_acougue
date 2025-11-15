<h1>Editar Produto: {{ $produto->nome }}</h1>

<form action="/produtos/{{ $produto->id }}" method="POST">
    @csrf
    @method('PUT') <div>
        <label for="categoria_id">Categoria:</label>
        <select id="categoria_id" name="categoria_id">
            <option value="">Selecione uma categoria</option>
            
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" 
                    @if ($categoria->id == $produto->categoria_id) selected @endif
                >
                    {{ $categoria->nome }}
                </option>
            @endforeach

        </select>
    </div>

    <div>
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" value="{{ $produto->nome }}">
    </div>

    <div>
        <label for="preco_venda">Preço de Venda (R$):</label>
        <input type="number" id="preco_venda" name="preco_venda" step="0.01" value="{{ $produto->preco_venda }}">
    </div>

    <div>
        <label for="unidade_medida">Unidade de Medida (ex: KG, UN):</label>
        <input type="text" id="unidade_medida" name="unidade_medida" value="{{ $produto->unidade_medida }}">
    </div>

    <div>
        <label for="codigo_barras">Código de Barras (Opcional):</label>
        <input type="text" id="codigo_barras" name="codigo_barras" value="{{ $produto->codigo_barras }}">
    </div>

    <div>
        <label for="estoque_atual">Estoque Atual:</label>
        <input type="number" id="estoque_atual" name="estoque_atual" step="0.001" min="0" value="{{ $produto->estoque_atual }}">
    </div>

    <div>
        <label for="estoque_minimo">Estoque Mínimo:</label>
        <input type="number" id="estoque_minimo" name="estoque_minimo" step="0.001" min="0" value="{{ $produto->estoque_minimo }}">
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="/produtos">Voltar para a lista</a>