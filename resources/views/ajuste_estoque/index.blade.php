<x-simple-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-boxes mr-2"></i>Ajustes de Estoque
            </h2>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
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
</x-simple-layout>

