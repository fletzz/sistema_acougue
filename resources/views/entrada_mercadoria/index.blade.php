<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entradas de Mercadoria
        </h2>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mx-4 my-6">
        <!-- Cabeçalho -->
        <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Entradas de Bovinos</h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors text-center font-medium">
                    Dashboard
                </a>
                <a href="{{ route('entrada_mercadoria.create') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-center font-medium">
                    Nova Entrada
                </a>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fornecedor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($entradas as $entrada)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $entrada->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $entrada->fornecedor->razao_social ?? 'Sem fornecedor' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $entrada->data_entrada->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $entrada->usuario->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('entrada_mercadoria.show', $entrada->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors">
                                Ver Detalhes
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Nenhuma entrada encontrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if(method_exists($entradas, 'hasPages') && $entradas->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $entradas->links() }}
        </div>
        @endif
    </div>
</x-app-layout>

