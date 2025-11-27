<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-boxes mr-2"></i>Estoque de Produtos
            </h2>
            <button onclick="document.getElementById('novoProdutoModal').classList.remove('hidden')" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Novo Produto
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        {{-- CONTEÚDO PRINCIPAL: ESTOQUE --}}
        <main class="flex-1 px-8 py-6 overflow-y-auto">
            {{-- Cabeçalho da página --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Estoque</h1>
                    <p class="text-sm text-gray-500">
                        Acompanhe seus produtos, níveis de estoque e faça ajustes quando necessário.
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    {{-- Botão que abre o MODAL de novo produto --}}
                    <button
                        type="button"
                        id="openNovoProdutoModal"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-sm hover:bg-blue-700 transition">
                        + Novo produto
                    </button>
                </div>
            </div>

            @php
                $totalProdutos = $produtos->count();
                $estoqueBaixo = $produtos->filter(fn ($p) => $p->estoque_atual < $p->estoque_minimo)->count();
                $semEstoque   = $produtos->filter(fn ($p) => $p->estoque_atual <= 0)->count();
            @endphp

            {{-- Cards de resumo do estoque --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase">Total de produtos</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProdutos }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase">Produtos com estoque baixo</p>
                    <p class="mt-2 text-3xl font-bold text-amber-500">{{ $estoqueBaixo }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase">Sem estoque</p>
                    <p class="mt-2 text-3xl font-bold text-red-500">{{ $semEstoque }}</p>
                </div>
            </div>

            {{-- Tabela de produtos --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-800">Lista de produtos</h2>
                    <input
                        type="text"
                        id="busca-produto-estoque"
                        placeholder="Buscar por nome ou código de barras..."
                        class="w-64 rounded-lg border border-gray-200 px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">ID</th>
                                <th class="px-4 py-3 text-left">Produto</th>
                                <th class="px-4 py-3 text-left">Categoria</th>
                                <th class="px-4 py-3 text-right">Preço (R$)</th>
                                <th class="px-4 py-3 text-center">Un.</th>
                                <th class="px-4 py-3 text-right">Estoque atual</th>
                                <th class="px-4 py-3 text-right">Estoque mínimo</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="tabela-estoque-body">
                            @foreach ($produtos as $produto)
                                @php
                                    $status = 'OK';
                                    $badgeClasses = 'bg-emerald-50 text-emerald-700';
                                    if ($produto->estoque_atual <= 0) {
                                        $status = 'Sem estoque';
                                        $badgeClasses = 'bg-red-50 text-red-700';
                                    } elseif ($produto->estoque_atual < $produto->estoque_minimo) {
                                        $status = 'Baixo';
                                        $badgeClasses = 'bg-amber-50 text-amber-700';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 produto-row">
                                    <td class="px-4 py-3 text-xs text-gray-400 align-middle">
                                        #{{ $produto->id }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 align-middle">
                                        {{ $produto->nome }}
                                        @if($produto->codigo_barras)
                                            <div class="text-xs text-gray-400">Código: {{ $produto->codigo_barras }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 align-middle">
                                        {{ $produto->categoria->nome ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right align-middle">
                                        R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600 text-center align-middle">
                                        {{ $produto->unidade_medida }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right align-middle">
                                        {{ number_format($produto->estoque_atual, 3, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 text-right align-middle">
                                        {{ number_format($produto->estoque_minimo, 3, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center align-middle">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right align-middle">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="{{ url('/produtos/' . $produto->id . '/edit') }}"
                                               class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                                Editar
                                            </a>

                                            <form action="{{ url('/produtos/' . $produto->id) }}"
                                                  method="POST"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Deseja realmente deletar este produto?')"
                                                        class="text-xs font-medium text-red-600 hover:text-red-800">
                                                    Deletar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        {{-- MODAL: NOVO PRODUTO --}}
        <div id="novoProdutoModal"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">Cadastrar novo produto</h2>
                    <button id="closeNovoProdutoModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                        &times;
                    </button>
                </div>

                <div class="px-6 py-4 overflow-y-auto">
                    {{-- FORM DE CREATE AQUI DENTRO --}}
                    <form action="{{ url('/produtos') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="categoria_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Categoria
                                </label>
                                <select
                                    id="categoria_id"
                                    name="categoria_id"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="nome" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Nome do produto
                                </label>
                                <input
                                    type="text"
                                    id="nome"
                                    name="nome"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preco_venda" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Preço de venda (R$)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="preco_venda"
                                    name="preco_venda"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>

                            <div>
                                <label for="unidade_medida" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Unidade de medida (ex: KG, UN)
                                </label>
                                <input
                                    type="text"
                                    id="unidade_medida"
                                    name="unidade_medida"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="codigo_barras" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                Código de barras (opcional)
                            </label>
                            <input
                                type="text"
                                id="codigo_barras"
                                name="codigo_barras"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="estoque_atual" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Estoque atual
                                </label>
                                <input
                                    type="number"
                                    step="0.001"
                                    min="0"
                                    id="estoque_atual"
                                    name="estoque_atual"
                                    value="0"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>

                            <div>
                                <label for="estoque_minimo" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Estoque mínimo
                                </label>
                                <input
                                    type="number"
                                    step="0.001"
                                    min="0"
                                    id="estoque_minimo"
                                    name="estoque_minimo"
                                    value="0"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end space-x-3">
                            <button type="button"
                                    id="closeNovoProdutoModalFooter"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-sm hover:bg-blue-700 transition">
                                Salvar produto
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

    {{-- JS: filtro da tabela + controle do modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Filtro da tabela
            const searchInput = document.querySelector('input[type="search"]');
            const tableBody = document.getElementById('tabela-estoque-body');
            const rows = tableBody.getElementsByTagName('tr');

            inputBusca.addEventListener('keyup', () => {
                const termo = inputBusca.value.toLowerCase();

                linhas.forEach(linha => {
                    const texto = linha.innerText.toLowerCase();
                    linha.style.display = texto.includes(termo) ? '' : 'none';
                });
            });

            // Modal novo produto
            const modal = document.getElementById('novoProdutoModal');
            const openBtn = document.getElementById('openNovoProdutoModal');
            const closeBtn = document.getElementById('closeNovoProdutoModal');
            const closeBtnFooter = document.getElementById('closeNovoProdutoModalFooter');

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
