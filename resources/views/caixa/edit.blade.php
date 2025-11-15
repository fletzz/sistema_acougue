<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Caixa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('caixa.update', $caixa->id) }}">
                @csrf
                @method('PUT')
                
                <div>
                    <label>Descrição</label>
                    <input type="text" name="descricao" value="{{ $caixa->descricao }}" required>
                </div>

                <div>
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aberto" {{ $caixa->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="fechado" {{ $caixa->status == 'fechado' ? 'selected' : '' }}>Fechado</option>
                    </select>
                </div>

                <div>
                    <label>Saldo Atual</label>
                    <input type="number" name="saldo_atual" value="{{ $caixa->saldo_atual }}" step="0.01" min="0" required>
                </div>

                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>
</x-app-layout>

