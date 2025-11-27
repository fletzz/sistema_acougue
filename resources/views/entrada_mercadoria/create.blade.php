<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nova Entrada de Nota Fiscal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('entrada_mercadoria.store') }}" id="formEntrada">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    
                    <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-blue-200 flex flex-col items-center justify-center text-center">
                        <svg class="w-12 h-12 text-blue-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm text-gray-600 mb-4">Arraste o XML da NFe aqui ou</p>
                        <button type="button" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md font-medium hover:bg-blue-200 transition">
                            Selecionar Arquivo
                        </button>
                        <p class="text-xs text-gray-400 mt-2">(Funcionalidade futura)</p>
                    </div>

                    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Dados da Nota</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fornecedor</label>
                                <select name="fornecedor_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Selecione o fornecedor...</option>
                                    @foreach($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}">{{ $fornecedor->razao_social }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número da Nota</label>
                                <input type="text" name="numero_nota" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="000000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Série</label>
                                <input type="text" name="serie" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Emissão</label>
                                <input type="date" name="data_emissao" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Entrada</label>
                                <input type="date" name="data_entrada" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Chave de Acesso (44 dígitos)</label>
                                <div class="relative">
                                    <input type="text" name="chave_acesso" class="w-full border-gray-300 rounded-md shadow-sm pl-10" placeholder="0000 0000 0000 0000 0000 0000 0000 0000 0000 0000 0000">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 12v12m0-12l-2-2m2 2l2-2m6 12v-4m0 4h-2m2 0h2m-2-4h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total de Itens</p>
                            <p class="text-2xl font-bold text-gray-800" id="card-total-itens">0</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Valor Total da Nota</p>
                            <p class="text-2xl font-bold text-gray-800" id="card-valor-total">R$ 0,00</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500 flex items-center">
                        <div class="flex items-center h-full">
                            <input type="checkbox" id="gerar_financeiro" name="gerar_financeiro" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" checked>
                            <label for="gerar_financeiro" class="ml-3 text-sm font-medium text-gray-700">
                                Gerar Contas a Pagar automaticamente
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Conferência de Produtos</h3>
                        <button type="button" id="adicionar-item" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-bold hover:bg-green-700 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Adicionar Item
                        </button>
                    </div>

                    <div class="p-4">
                        <div id="itens-container">
                            <div class="item-row grid grid-cols-12 gap-3 mb-4 p-4 border rounded-lg bg-gray-50 items-end relative">
                                
                                <div class="col-span-12 md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Produto (Sistema)</label>
                                    <select name="produto_id[]" class="produto-entrada w-full text-sm border-gray-300 rounded-md">
                                        <option value="">Selecione...</option>
                                        <option value="__NOVO__" class="font-bold text-blue-600">+ Cadastrar Novo</option>
                                        @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-12 md:col-span-9 campos-produto-novo hidden bg-blue-50 p-3 rounded mb-2">
                                    <p class="text-xs text-blue-600 font-bold mb-2">Cadastrando Novo Produto Rápido:</p>
                                    <div class="grid grid-cols-3 gap-2">
                                        <input type="text" name="nome_produto[]" class="nome-produto-novo text-sm border-gray-300 rounded" placeholder="Nome do Produto">
                                        <select name="categoria_id[]" class="categoria-produto-novo text-sm border-gray-300 rounded">
                                            <option value="">Categoria...</option>
                                            @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="unidade_medida[]" class="unidade-produto-novo text-sm border-gray-300 rounded" placeholder="UN/KG" value="KG">
                                    </div>
                                </div>

                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Qtd (Peso)</label>
                                    <input type="number" name="quantidade[]" class="quantidade-entrada w-full text-sm border-gray-300 rounded-md" step="0.001" placeholder="0.000">
                                </div>

                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Lote</label>
                                    <input type="text" name="lote[]" class="w-full text-sm border-gray-300 rounded-md" placeholder="Lote...">
                                </div>

                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1 text-red-600">Validade *</label>
                                    <input type="date" name="validade[]" class="w-full text-sm border-gray-300 rounded-md border-l-4 border-l-red-400" required>
                                </div>

                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Custo Unit (R$)</label>
                                    <input type="number" name="preco_custo[]" class="preco-custo w-full text-sm border-gray-300 rounded-md" step="0.01" placeholder="0.00">
                                </div>

                                <div class="col-span-12 md:col-span-1 flex justify-center pb-1">
                                    <button type="button" class="remover-item text-red-500 hover:text-red-700 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300">Cancelar</a>
                    
                    <input type="hidden" name="itens" id="itens-json">
                    
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Confirmar Entrada
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa eventos na primeira linha
            const firstRow = document.querySelector('.item-row');
            if(firstRow) attachEventListeners(firstRow);

            // Adicionar nova linha
            document.getElementById('adicionar-item').addEventListener('click', function() {
                const container = document.getElementById('itens-container');
                const firstRow = container.querySelector('.item-row');
                const novoItem = firstRow.cloneNode(true);
                
                // Limpa os valores
                novoItem.querySelectorAll('input').forEach(input => input.value = '');
                novoItem.querySelectorAll('select').forEach(select => select.value = '');
                
                // Reseta visual do "Novo Produto"
                novoItem.querySelector('.campos-produto-novo').classList.add('hidden');
                
                container.appendChild(novoItem);
                attachEventListeners(novoItem);
                atualizarTotais();
            });

            // Função para remover linha
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remover-item')) {
                    const rows = document.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        e.target.closest('.item-row').remove();
                        atualizarTotais();
                    } else {
                        alert('A nota precisa ter pelo menos um item.');
                    }
                }
            });

            // Lógica do "Cadastrar Novo" e Totais
            function attachEventListeners(row) {
                const select = row.querySelector('.produto-entrada');
                const camposNovo = row.querySelector('.campos-produto-novo');
                const qtdInput = row.querySelector('.quantidade-entrada');
                const precoInput = row.querySelector('.preco-custo');

                // Toggle Produto Novo
                select.addEventListener('change', function() {
                    if (this.value === '__NOVO__') {
                        camposNovo.classList.remove('hidden');
                        // Torna obrigatórios os campos novos
                        camposNovo.querySelectorAll('input, select').forEach(el => el.required = true);
                    } else {
                        camposNovo.classList.add('hidden');
                        camposNovo.querySelectorAll('input, select').forEach(el => el.required = false);
                    }
                });

                // Atualiza totais ao digitar
                qtdInput.addEventListener('input', atualizarTotais);
                precoInput.addEventListener('input', atualizarTotais);
            }

            // Função para atualizar os Cards de Resumo
            function atualizarTotais() {
                let totalItens = 0;
                let valorTotal = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const qtd = parseFloat(row.querySelector('.quantidade-entrada').value) || 0;
                    const preco = parseFloat(row.querySelector('.preco-custo').value) || 0;
                    
                    totalItens += 1; // Contando linhas, se quiser somar peso use += qtd
                    valorTotal += (qtd * preco);
                });

                document.getElementById('card-total-itens').innerText = totalItens;
                document.getElementById('card-valor-total').innerText = 'R$ ' + valorTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            }

            // Preparar JSON antes de enviar (Sua lógica original mantida)
            document.getElementById('formEntrada').addEventListener('submit', function(e) {
                const itens = [];
                let erro = false;

                document.querySelectorAll('.item-row').forEach(row => {
                    const produtoId = row.querySelector('.produto-entrada').value;
                    const qtd = row.querySelector('.quantidade-entrada').value;
                    const custo = row.querySelector('.preco-custo').value;
                    
                    // Novos campos
                    const lote = row.querySelector('input[name="lote[]"]').value;
                    const validade = row.querySelector('input[name="validade[]"]').value;

                    if (!produtoId || !qtd || !validade) {
                        erro = true;
                        return;
                    }

                    let itemData = {
                        produto_id: produtoId,
                        quantidade: parseFloat(qtd),
                        preco_custo: parseFloat(custo),
                        lote: lote,
                        validade: validade
                    };

                    // Se for produto novo, pega os dados extras
                    if (produtoId === '__NOVO__') {
                        itemData.novo_produto = true;
                        itemData.nome_produto = row.querySelector('.nome-produto-novo').value;
                        itemData.categoria_id = row.querySelector('.categoria-produto-novo').value;
                        itemData.unidade_medida = row.querySelector('.unidade-produto-novo').value;
                    }

                    itens.push(itemData);
                });

                if (erro) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios (Produto, Qtd e Validade).');
                    return false;
                }

                document.getElementById('itens-json').value = JSON.stringify(itens);
            });
        });
    </script>
</x-app-layout>