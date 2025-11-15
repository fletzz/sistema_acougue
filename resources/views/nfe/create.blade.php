<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gerar NFe
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('nfe.store') }}">
                @csrf
                
                <div>
                    <label>Venda</label>
                    <select name="venda_id" required>
                        <option value="">Selecione</option>
                        @foreach($vendas as $venda)
                        <option value="{{ $venda->id }}" {{ $vendaId == $venda->id ? 'selected' : '' }}>
                            Venda #{{ $venda->id }} - R$ {{ number_format($venda->valor_total_final, 2, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Emitente</label>
                    <select name="emitente_id" required>
                        <option value="">Selecione</option>
                        @foreach($emitentes as $emitente)
                        <option value="{{ $emitente->id }}">{{ $emitente->razao_social }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Ambiente</label>
                    <select name="ambiente">
                        <option value="2">Homologação</option>
                        <option value="1">Produção</option>
                    </select>
                </div>

                <button type="submit">Gerar NFe</button>
            </form>
        </div>
    </div>
</x-app-layout>

