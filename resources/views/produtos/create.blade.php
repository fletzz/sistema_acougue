{{-- resources/views/produtos/create.blade.php --}}

<x-app-layout>
    <div class="px-8 py-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Cadastrar novo produto</h1>
                <p class="text-sm text-gray-500">Preencha os dados do produto para adicioná-lo ao estoque.</p>
            </div>

            <a href="{{ route('produtos.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                Voltar para a lista
            </a>
        </div>

        {{-- Erros de validação --}}
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

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form action="{{ url('/produtos') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Linha 1: Categoria + Nome --}}
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
                                <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
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
                            value="{{ old('nome') }}"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                </div>

                {{-- Linha 2: Preço + Unidade de medida --}}
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
                            value="{{ old('preco_venda') }}"
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
                            value="{{ old('unidade_medida') }}"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                </div>

                {{-- Linha 3: Código de barras --}}
                <div>
                    <label for="codigo_barras" class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                        Código de barras (opcional)
                    </label>
                    <input
                        type="text"
                        id="codigo_barras"
                        name="codigo_barras"
                        value="{{ old('codigo_barras') }}"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                {{-- Linha 4: Estoque atual / mínimo --}}
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
                            value="{{ old('estoque_atual', 0) }}"
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
                            value="{{ old('estoque_minimo', 0) }}"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                </div>

                {{-- Botões --}}
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('produtos.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-sm hover:bg-blue-700 transition">
                        Salvar produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
