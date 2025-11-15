<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Caixas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 24px; font-weight: bold;">Caixas</h2>
                <div>
                    <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
                    <a href="{{ route('caixa.create') }}" style="padding: 10px 20px; background: #16a34a; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Novo Caixa</a>
                </div>
            </div>
            
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Saldo Atual</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caixas as $caixa)
                    <tr>
                        <td>{{ $caixa->descricao }}</td>
                        <td>{{ $caixa->status }}</td>
                        <td>R$ {{ number_format($caixa->saldo_atual, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('caixa.movimentacoes', $caixa->id) }}">Movimentações</a>
                            <a href="{{ route('caixa.edit', $caixa->id) }}">Editar</a>
                            @if($caixa->status == 'fechado')
                            <form method="POST" action="{{ route('caixa.abrir', $caixa->id) }}" class="inline">
                                @csrf
                                <button type="submit">Abrir</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('caixa.fechar', $caixa->id) }}" class="inline">
                                @csrf
                                <button type="submit">Fechar</button>
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

