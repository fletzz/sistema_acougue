<x-simple-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-cut mr-2"></i>Processo de Desossa
            </h2>
            <a href="{{ route('entrada_mercadoria.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card de Resumo da Origem -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-blue-500">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Origem da Matéria-Prima</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nota Fiscal</p>
                            <p class="font-semibold">#{{ $entrada->numero_nota }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fornecedor</p>
                            <p class="font-semibold">{{ $entrada->fornecedor ? $entrada->fornecedor->razao_social : 'Não informado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Peça Bruta</p>
                            <p class="font-semibold">{{ $itemEntrada->produto->nome }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Peso Original</p>
                            <p class="font-semibold">{{ number_format($itemEntrada->quantidade, 3, ',', '.') }} kg</p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('transformacao.store') }}" id="formDesossa">
                @csrf
                <input type="hidden" name="entrada_id" value="{{ $entrada->id }}">
                <input type="hidden" name="item_entrada_id" value="{{ $itemEntrada->id }}">
                
                <!-- Seção de Cortes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Resultado da Desossa</h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('entrada_mercadoria.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm font-bold hover:bg-gray-600 flex items-center">
                                    <i class="fas fa-times-circle mr-2"></i>Cancelar
                                </a>
                                <button type="button" id="adicionar-corte" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-bold hover:bg-green-700 flex items-center">
                                    <i class="fas fa-plus-circle mr-2"></i>Adicionar Corte
                                </button>
                            </div>
                        </div>

                        <div id="cortes-container">
                            <!-- Linha de exemplo (será clonada) -->
                            <div class="corte-row grid grid-cols-12 gap-4 mb-4 p-4 border rounded-lg bg-gray-50">
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Corte/Produto Final</label>
                                    <select name="produto_id[]" class="produto-corte w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Selecione o corte...</option>
                                        @foreach($cortes as $corte)
                                            <option value="{{ $corte->id }}" data-unidade="{{ $corte->unidade_medida }}">
                                                {{ $corte->nome }} ({{ $corte->unidade_medida }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                                    <input type="number" name="peso[]" class="peso-corte w-full rounded-md border-gray-300 shadow-sm" step="0.001" min="0.001" required>
                                </div>
                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">% Rendimento</label>
                                    <div class="rendimento-corte bg-gray-100 p-2 rounded-md text-center font-mono">0.00%</div>
                                </div>
                                <div class="col-span-6 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Custo (R$/kg)</label>
                                    <div class="custo-corte bg-gray-100 p-2 rounded-md text-center font-mono">R$ 0,00</div>
                                </div>
                                <div class="col-span-6 md:col-span-2 flex items-end">
                                    <button type="button" class="remover-corte text-red-500 hover:text-red-700 p-2">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumo da Desossa -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 sticky bottom-0 border-t-4 border-blue-500">
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-700">Peso Original</p>
                                <p class="text-xl font-bold" id="peso-original">{{ number_format($itemEntrada->quantidade, 3, ',', '.') }} kg</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-sm text-green-700">Total de Cortes</p>
                                <p class="text-xl font-bold" id="total-cortes">0.000 kg</p>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <p class="text-sm text-yellow-700">Quebra/Perda</p>
                                <p class="text-xl font-bold" id="quebra-perda">0.000 kg <span id="quebra-percentual" class="text-sm">(0.00%)</span></p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-sm text-purple-700">Rendimento Total</p>
                                <p class="text-xl font-bold" id="rendimento-total">0.00%</p>
                            </div>
                        </div>
                        
                        <div id="alerta-quebra" class="hidden mt-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700">
                            <p class="font-bold">Atenção!</p>
                            <p>A quebra está acima de 5%. Verifique se todos os cortes foram lançados corretamente.</p>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('entrada_mercadoria.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                <i class="fas fa-save mr-2"></i>Salvar Desossa
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pesoOriginal = parseFloat({{ $itemEntrada->quantidade }});
            const custoOriginal = parseFloat({{ $itemEntrada->preco_custo }});
            
            // Clonar linha de corte
            document.getElementById('adicionar-corte').addEventListener('click', function() {
                const container = document.getElementById('cortes-container');
                const template = container.querySelector('.corte-row').cloneNode(true);
                
                // Limpar valores
                template.querySelectorAll('input').forEach(input => input.value = '');
                template.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                template.querySelector('.rendimento-corte').textContent = '0.00%';
                template.querySelector('.custo-corte').textContent = 'R$ 0,00';
                
                // Adicionar evento de remoção
                const btnRemover = template.querySelector('.remover-corte');
                btnRemover.addEventListener('click', function() {
                    template.remove();
                    atualizarResumo();
                });
                
                // Adicionar eventos de cálculo
                const inputPeso = template.querySelector('.peso-corte');
                inputPeso.addEventListener('input', atualizarResumo);
                
                container.appendChild(template);
                atualizarResumo();
            });
            
            // Remover linha de corte
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remover-corte')) {
                    const rows = document.querySelectorAll('.corte-row');
                    if (rows.length > 1) {
                        e.target.closest('.corte-row').remove();
                        atualizarResumo();
                    }
                }
            });
            
            // Atualizar resumo
            function atualizarResumo() {
                let totalCortes = 0;
                
                // Atualizar totais
                document.querySelectorAll('.corte-row').forEach(row => {
                    const peso = parseFloat(row.querySelector('.peso-corte').value) || 0;
                    totalCortes += peso;
                    
                    // Calcular % de rendimento
                    const rendimento = peso > 0 ? (peso / pesoOriginal) * 100 : 0;
                    row.querySelector('.rendimento-corte').textContent = rendimento.toFixed(2) + '%';
                    
                    // Calcular custo
                    const custo = peso > 0 ? (custoOriginal / pesoOriginal) * peso : 0;
                    row.querySelector('.custo-corte').textContent = 'R$ ' + custo.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                });
                
                // Atualizar totais gerais
                const quebra = pesoOriginal - totalCortes;
                const percentualQuebra = (quebra / pesoOriginal) * 100;
                const rendimentoTotal = ((pesoOriginal - quebra) / pesoOriginal) * 100;
                
                document.getElementById('total-cortes').textContent = totalCortes.toLocaleString('pt-BR', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                }) + ' kg';
                
                const quebraElement = document.getElementById('quebra-perda');
                quebraElement.textContent = quebra.toLocaleString('pt-BR', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                }) + ' kg';
                
                const percentualElement = document.createElement('span');
                percentualElement.id = 'quebra-percentual';
                percentualElement.className = 'text-sm';
                percentualElement.textContent = `(${percentualQuebra.toFixed(2)}%)`;
                
                // Destacar se a quebra for maior que 5%
                if (percentualQuebra > 5) {
                    quebraElement.classList.add('text-red-600');
                    percentualElement.classList.add('text-red-600');
                    document.getElementById('alerta-quebra').classList.remove('hidden');
                } else {
                    quebraElement.classList.remove('text-red-600');
                    percentualElement.classList.remove('text-red-600');
                    document.getElementById('alerta-quebra').classList.add('hidden');
                }
                
                quebraElement.appendChild(percentualElement);
                document.getElementById('rendimento-total').textContent = rendimentoTotal.toFixed(2) + '%';
            }
            
            // Inicializar a primeira linha
            document.querySelector('.remover-corte').addEventListener('click', function() {
                const rows = document.querySelectorAll('.corte-row');
                if (rows.length > 1) {
                    this.closest('.corte-row').remove();
                    atualizarResumo();
                }
            });
            
            // Atualizar ao carregar a página
            atualizarResumo();
            
            // Validar antes de enviar o formulário
            document.getElementById('formDesossa').addEventListener('submit', function(e) {
                const cortes = [];
                let totalPeso = 0;
                
                document.querySelectorAll('.corte-row').forEach(row => {
                    const produtoId = row.querySelector('.produto-corte').value;
                    const peso = parseFloat(row.querySelector('.peso-corte').value) || 0;
                    
                    if (produtoId && peso > 0) {
                        cortes.push({
                            produto_id: produtoId,
                            peso: peso
                        });
                        totalPeso += peso;
                    }
                });
                
                if (cortes.length === 0) {
                    e.preventDefault();
                    alert('Adicione pelo menos um corte válido.');
                    return false;
                }
                
                // Verificar se a quebra é maior que 5% e confirmar
                const quebra = pesoOriginal - totalPeso;
                const percentualQuebra = (quebra / pesoOriginal) * 100;
                
                if (percentualQuebra > 5) {
                    if (!confirm(`A quebra está em ${percentualQuebra.toFixed(2)}%, que é superior a 5%. Deseja continuar mesmo assim?`)) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                return true;
            });
        });
    </script>
    @endpush
</x-simple-layout>
