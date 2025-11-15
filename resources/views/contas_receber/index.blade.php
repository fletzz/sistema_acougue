<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contas a Receber
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 24px; font-weight: bold;">Contas a Receber</h2>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>
            
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Venda</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contasReceber as $conta)
                    <tr>
                        <td>#{{ $conta->venda_id }}</td>
                        <td>{{ $conta->cliente->nome ?? 'N/A' }}</td>
                        <td>R$ {{ number_format($conta->valor_pago, 2, ',', '.') }}</td>
                        <td>{{ $conta->status ?? 'pendente' }}</td>
                        <td>
                            <a href="{{ route('contas_receber.show', $conta->id) }}">Ver</a>
                            @if(($conta->status ?? 'pendente') == 'pendente')
                            <form method="POST" action="{{ route('contas_receber.receber', $conta->id) }}" class="inline">
                                @csrf
                                <button type="submit">Marcar como Recebido</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

