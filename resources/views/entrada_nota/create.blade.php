<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nova Entrada de Nota
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('entrada_nota.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>

            <form method="POST" action="{{ route('entrada_nota.store') }}">
                @csrf
                
                <div>
                    <label>Fornecedor</label>
                    <select name="fornecedor_id" required>
                        <option value="">Selecione</option>
                        @foreach($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->razao_social }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Número da Nota</label>
                    <input type="text" name="numero_nota" required>
                </div>

                <div>
                    <label>Série</label>
                    <input type="text" name="serie" required>
                </div>

                <div>
                    <label>Chave de Acesso</label>
                    <input type="text" name="chave_acesso" required maxlength="44">
                </div>

                <div>
                    <label>Data Emissão</label>
                    <input type="date" name="data_emissao" required>
                </div>

                <div>
                    <label>Data Entrada</label>
                    <input type="date" name="data_entrada" required>
                </div>

                <div>
                    <label>Valor Total</label>
                    <input type="number" name="valor_total_nota" step="0.01" min="0" required>
                </div>

                <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <h3 style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">Produtos da Nota</h3>
                    
                    <div id="itens-container">
                        <div class="item-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: end;">
                            <div style="flex: 2;">
                                <label>Produto:</label>
                                <select name="produto_id[]" class="produto-nota" required style="width: 100%; padding: 8px;">
                                    <option value="">Selecione</option>
                                    @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label>Quantidade:</label>
                                <input type="number" name="quantidade[]" class="quantidade-nota" step="0.001" min="0.001" required style="width: 100%; padding: 8px;">
                            </div>
                            <div style="flex: 1;">
                                <label>Preço Custo (R$):</label>
                                <input type="number" name="preco_custo[]" class="preco-custo-nota" step="0.01" min="0" required style="width: 100%; padding: 8px;">
                            </div>
                            <div>
                                <button type="button" class="remover-item" style="padding: 8px 15px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer;">Remover</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="adicionar-item" style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 10px;">+ Adicionar Produto</button>
                </div>

                <input type="hidden" name="items" id="itens-json">

                <button type="submit" style="padding: 12px 30px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px;">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('adicionar-item').addEventListener('click', function() {
            const container = document.getElementById('itens-container');
            const novoItem = container.querySelector('.item-row').cloneNode(true);
            novoItem.querySelectorAll('input, select').forEach(input => {
                input.value = '';
            });
            container.appendChild(novoItem);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remover-item')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                } else {
                    alert('É necessário ter pelo menos um produto.');
                }
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const itens = [];
            document.querySelectorAll('.item-row').forEach(row => {
                const produtoId = row.querySelector('.produto-nota').value;
                const quantidade = row.querySelector('.quantidade-nota').value;
                const precoCusto = row.querySelector('.preco-custo-nota').value;
                
                if (produtoId && quantidade && precoCusto) {
                    itens.push({
                        produto_id: produtoId,
                        quantidade: parseFloat(quantidade),
                        preco_custo_unitario: parseFloat(precoCusto)
                    });
                }
            });

            if (itens.length === 0) {
                e.preventDefault();
                alert('Adicione pelo menos um produto.');
                return false;
            }

            document.getElementById('itens-json').value = JSON.stringify(itens);
        });
    </script>
</x-app-layout>

