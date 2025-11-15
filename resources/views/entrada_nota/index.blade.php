<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entradas de Notas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 24px; font-weight: bold;">Entradas de Notas Fiscais</h2>
                <div>
                    <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
                    <a href="{{ route('entrada_nota.create') }}" style="padding: 10px 20px; background: #ea580c; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Nova Entrada</a>
                </div>
            </div>
            
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Fornecedor</th>
                        <th>Data Entrada</th>
                        <th>Valor Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entradas as $entrada)
                    <tr>
                        <td>{{ $entrada->numero_nota }}</td>
                        <td>{{ $entrada->fornecedor->razao_social }}</td>
                        <td>{{ $entrada->data_entrada->format('d/m/Y') }}</td>
                        <td>R$ {{ number_format($entrada->valor_total_nota, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('entrada_nota.show', $entrada->id) }}">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

