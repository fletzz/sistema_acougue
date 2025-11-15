<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajustes de Estoque
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('ajuste_estoque.create') }}" class="mb-4 inline-block">Novo Ajuste</a>
            
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Motivo</th>
                        <th>Usuário</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ajustes as $ajuste)
                    <tr>
                        <td>{{ $ajuste->produto->nome }}</td>
                        <td>{{ $ajuste->quantidade > 0 ? '+' : '' }}{{ $ajuste->quantidade }}</td>
                        <td>{{ $ajuste->motivo }}</td>
                        <td>{{ $ajuste->usuario->name }}</td>
                        <td>{{ $ajuste->data_ajuste->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

