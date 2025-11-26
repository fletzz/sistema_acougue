{{-- resources/views/vendas/create.blade.php --}}

<x-app-layout>
    <x-slot name="title">Estoque</x-slot>
    <div class="min-h-screen bg-gray-100 flex flex-col">

        {{-- TOPO / HEADER DO PDV --}}
        <header class="flex items-center justify-between px-8 py-4 bg-white shadow-sm">

            <div class="flex items-center space-x-3">
                {{-- Botão para abrir o modal com vendas finalizadas --}}
                <button
                    type="button"
                    id="openVendasModal"
                    class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    Ver vendas finalizadas
                </button>

                {{-- Botão de sair do PDV (volta para o dashboard) --}}
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Sair
                </a>
            </div>
        </header>

        {{-- CONTEÚDO PRINCIPAL DO PDV --}}
        <main class="flex-1 px-8 py-6">
            {{-- Mensagens de erro --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <strong class="font-semibold">Ops! Algo deu errado:</strong>
                    <ul class="list-disc pl-5 mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mensagem de sucesso --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('/vendas') }}" method="POST" id="form-venda" class="h-full">
                @csrf

                <div class="grid grid-cols-12 gap-6 h-full">

                    {{-- COLUNA ESQUERDA: CATÁLOGO DE PRODUTOS --}}
                    <div class="col-span-5 bg-white rounded-2xl shadow-sm p-5 flex flex-col">
                        <h3 class="text-base font-semibold text-gray-800 mb-3">Catálogo de produtos</h3>

                        <div class="mb-3">
                            <input
                                type="text"
                                id="busca-produto"
                                placeholder="Buscar produto..."
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div id="catalogo-produtos"
                             class="flex-1 overflow-y-auto border border-gray-100 rounded-xl divide-y divide-gray-100 text-sm">
                            @foreach ($produtos as $produto)
                                <button
                                    type="button"
                                    class="w-full text-left px-3 py-2 hover:bg-gray-50 flex justify-between items-center produto-item"
                                    onclick="adicionarAoCarrinho({{ $produto->id }}, '{{ $produto->nome }}', {{ $produto->preco_venda }})"
                                >
                                    <span>{{ $produto->nome }}</span>
                                    <span class="text-gray-500 text-xs">
                                        R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- COLUNA DIREITA: INFORMAÇÕES DA VENDA / CARRINHO --}}
                    <div class="col-span-7 bg-white rounded-2xl shadow-sm p-5 flex flex-col">
                        <h3 class="text-base font-semibold text-gray-800 mb-3">Informações da venda</h3>

                        {{-- Cliente --}}
                        <div class="mb-4">
                            <label for="cliente_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                Cliente (opcional, para fiado)
                            </label>
                            <select
                                id="cliente_id"
                                name="cliente_id"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Consumidor final (padrão)</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Carrinho --}}
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">Carrinho</h4>
                                <span class="text-xs text-gray-400">
                                    Clique em um produto no catálogo para adicioná-lo aqui
                                </span>
                            </div>

                            <div id="carrinho-items"
                                 class="min-h-[140px] rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 space-y-2 text-sm">
                                {{-- itens do carrinho via JS --}}
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Total da venda</span>
                                <span class="text-2xl font-bold text-gray-900">
                                    R$ <span id="total-venda">0.00</span>
                                </span>
                            </div>
                        </div>

                        {{-- Forma de pagamento --}}
                        <div class="mt-2 mb-4">
                            <label for="forma_pagamento_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                Forma de pagamento
                            </label>
                            <select
                                id="forma_pagamento_id"
                                name="forma_pagamento_id"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                @foreach ($formaPagamentos as $forma)
                                    <option value="{{ $forma->id }}">{{ $forma->descricao }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Campos ocultos para envio ao backend --}}
                        <input type="hidden" name="items" id="hidden-items">
                        <input type="hidden" name="valor_total_final" id="hidden-total">

                        {{-- Botão finalizar --}}
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-end">
                            <button
                                type="submit"
                                class="px-6 py-3 text-sm font-semibold rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition"
                            >
                                Finalizar venda
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </main>

        {{-- MODAL: VENDAS FINALIZADAS --}}
        <div id="vendasModal"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">Vendas finalizadas</h2>
                    <button id="closeVendasModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                        &times;
                    </button>
                </div>

                <div class="px-6 py-4 overflow-y-auto text-sm text-gray-700">
                    <div class="grid grid-cols-4 font-semibold text-xs text-gray-400 pb-2 border-b border-gray-100">
                        <span>Data</span>
                        <span>Cliente</span>
                        <span>Total</span>
                        <span class="text-right">Status</span>
                    </div>

                    @forelse ($vendasRecentes as $venda)
                        <div class="grid grid-cols-4 items-center py-2 border-b border-gray-50">
                            <span>
                                {{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}
                            </span>
                            <span>{{ $venda->cliente->nome ?? 'Consumidor final' }}</span>
                            <span>
                                R$ {{ number_format($venda->valor_total_final, 2, ',', '.') }}
                            </span>
                            <span class="text-right">
                                {{ ucfirst($venda->status ?? 'finalizada') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 mt-2">
                            Nenhuma venda finalizada encontrada.
                        </p>
                    @endforelse
                </div>

                <div class="px-6 py-3 border-t border-gray-100 flex justify-end">
                    <button id="closeVendasModalFooter"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Fechar
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT: CARRINHO + MODAL --}}
    <script>
        // ============================
        // LÓGICA DO CARRINHO (MESMA QUE O SENHOR JÁ TINHA)
        // ============================
        let carrinho = [];

        function adicionarAoCarrinho(id, nome, preco) {
            let quantidade = prompt(`Qual a quantidade de "${nome}"?`, 1);
            quantidade = parseFloat(quantidade);

            if (quantidade > 0) {
                carrinho.push({
                    produto_id: id,
                    nome: nome,
                    quantidade: quantidade,
                    preco_unitario: preco
                });

                renderizarCarrinho();
            }
        }

        function renderizarCarrinho() {
            let carrinhoHTML = '';
            let total = 0;

            carrinho.forEach((item, index) => {
                let subtotalItem = item.quantidade * item.preco_unitario;
                total += subtotalItem;

                carrinhoHTML += `
                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 shadow-sm">
                        <span>${item.quantidade}x ${item.nome} (R$ ${item.preco_unitario.toFixed(2)})</span>
                        <div class="flex items-center space-x-3">
                            <span class="font-medium">R$ ${subtotalItem.toFixed(2)}</span>
                            <button type="button"
                                    class="text-xs text-red-500 hover:text-red-700"
                                    onclick="removerDoCarrinho(${index})">
                                X
                            </button>
                        </div>
                    </div>
                `;
            });

            document.getElementById('carrinho-items').innerHTML = carrinhoHTML;
            document.getElementById('total-venda').innerText = total.toFixed(2);
        }

        function removerDoCarrinho(index) {
            carrinho.splice(index, 1);
            renderizarCarrinho();
        }

        document.getElementById('busca-produto').addEventListener('keyup', function () {
            let filtro = this.value.toLowerCase();
            let items = document.querySelectorAll('#catalogo-produtos .produto-item');

            items.forEach(item => {
                if (item.innerText.toLowerCase().includes(filtro)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });

        document.getElementById('form-venda').addEventListener('submit', function () {
            document.getElementById('hidden-items').value = JSON.stringify(carrinho);
            let total = document.getElementById('total-venda').innerText;
            document.getElementById('hidden-total').value = total;
        });

        // ============================
        // CONTROLE DO MODAL DE VENDAS
        // ============================
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('vendasModal');
            const openBtn = document.getElementById('openVendasModal');
            const closeBtn = document.getElementById('closeVendasModal');
            const closeBtnFooter = document.getElementById('closeVendasModalFooter');

            function openModal() {
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
            }

            openBtn?.addEventListener('click', openModal);
            closeBtn?.addEventListener('click', closeModal);
            closeBtnFooter?.addEventListener('click', closeModal);

            modal?.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>
