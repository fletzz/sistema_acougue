<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nova Transformação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('transformacao.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>

            <form method="POST" action="{{ route('transformacao.store') }}" id="formTransformacao">
                @csrf
                
                <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Produto de Origem (Carne Bruta)</h3>
                    
                    <div style="margin-bottom: 15px;">
                        <label>Produto:</label>
                        <select name="produto_origem_id" id="produto_origem_id" required style="width: 100%; padding: 8px;">
                            <option value="">Selecione o produto</option>
                            @foreach($produtos as $produto)
                            <option value="{{ $produto->id }}" data-estoque="{{ $produto->estoque_atual }}" data-unidade="{{ $produto->unidade_medida }}">
                                {{ $produto->nome }} (Estoque: {{ number_format($produto->estoque_atual, 3, ',', '.') }} {{ $produto->unidade_medida }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>Quantidade a Transformar:</label>
                        <input type="number" name="quantidade_origem" id="quantidade_origem" step="0.001" min="0.001" required style="width: 100%; padding: 8px;">
                        <small id="estoque_info" style="color: #6b7280;"></small>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>Observação:</label>
                        <textarea name="observacao" style="width: 100%; padding: 8px;"></textarea>
                    </div>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Produtos Destino (Cortes e Restos)</h3>
                    
                    <div id="itens-container">
                        <div class="item-row" style="margin-bottom: 15px; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <div style="margin-bottom: 10px;">
                                <label>Produto:</label>
                                <select name="produto_destino[]" class="produto-destino" required style="width: 100%; padding: 8px;">
                                    <option value="">Selecione um produto existente</option>
                                    <option value="__NOVO__">➕ Cadastrar Corte/Resto Novo</option>
                                    @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="campos-produto-existente" style="display: flex; gap: 10px; align-items: end;">
                                <div style="flex: 1;">
                                    <label>Quantidade:</label>
                                    <input type="number" name="quantidade_destino[]" class="quantidade-destino" step="0.001" min="0.001" required style="width: 100%; padding: 8px;">
                                </div>
                                <div style="flex: 1;">
                                    <label>Tipo:</label>
                                    <select name="tipo_destino[]" class="tipo-destino" required style="width: 100%; padding: 8px;">
                                        <option value="corte">Corte</option>
                                        <option value="resto">Resto (Sebo, Osso, etc)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="campos-produto-novo" style="display: none; margin-top: 10px; padding: 15px; background: #f3f4f6; border-radius: 6px;">
                                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                                    <div>
                                        <label>Nome do Produto:</label>
                                        <input type="text" name="nome_produto[]" class="nome-produto-novo" style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label>Categoria:</label>
                                        <select name="categoria_id[]" class="categoria-produto-novo" style="width: 100%; padding: 8px;">
                                            <option value="">Selecione</option>
                                            @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label>Unidade Medida:</label>
                                        <input type="text" name="unidade_medida[]" class="unidade-produto-novo" placeholder="KG, UN, etc" style="width: 100%; padding: 8px;">
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                                    <div>
                                        <label>Preço de Venda (R$):</label>
                                        <input type="number" name="preco_venda[]" class="preco-venda-novo" step="0.01" min="0" style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label>Quantidade:</label>
                                        <input type="number" name="quantidade_destino[]" class="quantidade-destino-novo" step="0.001" min="0.001" style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label>Tipo:</label>
                                        <select name="tipo_destino[]" class="tipo-destino-novo" style="width: 100%; padding: 8px;">
                                            <option value="corte">Corte</option>
                                            <option value="resto">Resto</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Código de Barras:</label>
                                        <input type="text" name="codigo_barras[]" class="codigo-barras-novo" style="width: 100%; padding: 8px;">
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 10px;">
                                <button type="button" class="remover-item" style="padding: 8px 15px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer;">Remover</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="adicionar-item" style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">+ Adicionar Item</button>
                </div>

                <input type="hidden" name="itens" id="itens-json">

                <button type="submit" style="padding: 12px 30px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px;">Registrar Transformação</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('produto_origem_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const estoque = option.getAttribute('data-estoque');
            const unidade = option.getAttribute('data-unidade');
            document.getElementById('estoque_info').textContent = 'Estoque disponível: ' + parseFloat(estoque).toFixed(3) + ' ' + unidade;
        });

        function attachEventListeners(row) {
            const select = row.querySelector('.produto-destino');
            select.addEventListener('change', function() {
                const camposExistente = row.querySelector('.campos-produto-existente');
                const camposNovo = row.querySelector('.campos-produto-novo');
                
                if (this.value === '__NOVO__') {
                    camposExistente.style.display = 'none';
                    camposNovo.style.display = 'block';
                    row.querySelectorAll('.campos-produto-novo input, .campos-produto-novo select').forEach(input => {
                        input.required = true;
                    });
                } else {
                    camposExistente.style.display = 'flex';
                    camposNovo.style.display = 'none';
                    row.querySelectorAll('.campos-produto-novo input, .campos-produto-novo select').forEach(input => {
                        input.required = false;
                    });
                }
            });
        }

        document.querySelectorAll('.item-row').forEach(row => {
            attachEventListeners(row);
        });

        document.getElementById('adicionar-item').addEventListener('click', function() {
            const container = document.getElementById('itens-container');
            const novoItem = container.querySelector('.item-row').cloneNode(true);
            novoItem.querySelectorAll('input, select').forEach(input => {
                input.value = '';
            });
            novoItem.querySelector('.campos-produto-novo').style.display = 'none';
            novoItem.querySelector('.campos-produto-existente').style.display = 'flex';
            container.appendChild(novoItem);
            attachEventListeners(novoItem);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remover-item')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                } else {
                    alert('É necessário ter pelo menos um item de destino.');
                }
            }
        });

        document.getElementById('formTransformacao').addEventListener('submit', function(e) {
            const itens = [];
            document.querySelectorAll('.item-row').forEach(row => {
                const produtoSelect = row.querySelector('.produto-destino');
                const produtoId = produtoSelect.value;
                
                if (produtoId === '__NOVO__') {
                    const nome = row.querySelector('.nome-produto-novo').value;
                    const categoriaId = row.querySelector('.categoria-produto-novo').value;
                    const unidade = row.querySelector('.unidade-produto-novo').value;
                    const precoVenda = row.querySelector('.preco-venda-novo').value;
                    const quantidade = row.querySelector('.quantidade-destino-novo').value;
                    const tipo = row.querySelector('.tipo-destino-novo').value;
                    const codigoBarras = row.querySelector('.codigo-barras-novo').value;
                    
                    if (nome && categoriaId && unidade && quantidade) {
                        itens.push({
                            novo_produto: true,
                            nome_produto: nome,
                            categoria_id: categoriaId,
                            unidade_medida: unidade,
                            preco_venda: precoVenda ? parseFloat(precoVenda) : 0,
                            quantidade: parseFloat(quantidade),
                            tipo: tipo,
                            codigo_barras: codigoBarras || null
                        });
                    }
                } else if (produtoId) {
                    const quantidade = row.querySelector('.quantidade-destino').value;
                    const tipo = row.querySelector('.tipo-destino').value;
                    
                    if (quantidade) {
                        itens.push({
                            produto_destino_id: produtoId,
                            quantidade: parseFloat(quantidade),
                            tipo: tipo
                        });
                    }
                }
            });

            if (itens.length === 0) {
                e.preventDefault();
                alert('Adicione pelo menos um produto de destino.');
                return false;
            }

            document.getElementById('itens-json').value = JSON.stringify(itens);
        });
    </script>
</x-app-layout>

