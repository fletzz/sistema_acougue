<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Novo Ajuste de Estoque
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('ajuste_estoque.store') }}">
                @csrf
                
                <div>
                    <label>Produto</label>
                    <select name="produto_id" required>
                        <option value="">Selecione</option>
                        @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Quantidade (positivo para adicionar, negativo para remover)</label>
                    <input type="number" name="quantidade" step="0.001" required>
                </div>

                <div>
                    <label>Motivo</label>
                    <textarea name="motivo" required></textarea>
                </div>

                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>
</x-app-layout>

