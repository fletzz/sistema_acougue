<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Novo Caixa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('caixa.store') }}">
                @csrf
                
                <div>
                    <label>Descrição</label>
                    <input type="text" name="descricao" required>
                </div>

                <div>
                    <label>Saldo Inicial</label>
                    <input type="number" name="saldo_atual" step="0.01" min="0" value="0">
                </div>

                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>
</x-app-layout>

