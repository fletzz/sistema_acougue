<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entradas de Mercadoria
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 24px; font-weight: bold;">Entradas de Bovinos</h2>
                <div>
                    <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
                    <a href="{{ route('entrada_mercadoria.create') }}" style="padding: 10px 20px; background: #059669; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Nova Entrada</a>
                </div>
            </div>

            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 10px; text-align: left;">ID</th>
                        <th style="padding: 10px; text-align: left;">Fornecedor</th>
                        <th style="padding: 10px; text-align: left;">Data</th>
                        <th style="padding: 10px; text-align: left;">Usuário</th>
                        <th style="padding: 10px; text-align: left;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entradas as $entrada)
                    <tr>
                        <td style="padding: 10px;">#{{ $entrada->id }}</td>
                        <td style="padding: 10px;">{{ $entrada->fornecedor->razao_social ?? 'Sem fornecedor' }}</td>
                        <td style="padding: 10px;">{{ $entrada->data_entrada->format('d/m/Y') }}</td>
                        <td style="padding: 10px;">{{ $entrada->usuario->name }}</td>
                        <td style="padding: 10px;">
                            <a href="{{ route('entrada_mercadoria.show', $entrada->id) }}" style="color: #2563eb; text-decoration: underline;">Ver Detalhes</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

