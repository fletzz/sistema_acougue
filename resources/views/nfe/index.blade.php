<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notas Fiscais
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 24px; font-weight: bold;">Notas Fiscais</h2>
                <div>
                    <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
                    <a href="{{ route('nfe.create') }}" style="padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Nova NFe</a>
                </div>
            </div>
            
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Venda</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notasFiscais as $nfe)
                    <tr>
                        <td>{{ $nfe->numero }}</td>
                        <td>{{ $nfe->venda_id }}</td>
                        <td>{{ $nfe->destinatario->nome ?? 'N/A' }}</td>
                        <td>{{ $nfe->status }}</td>
                        <td>R$ {{ number_format($nfe->valor_total_nfe, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('nfe.show', $nfe->id) }}">Ver</a>
                            @if($nfe->status == 'digitacao')
                            <form method="POST" action="{{ route('nfe.autorizar', $nfe->id) }}" class="inline">
                                @csrf
                                <button type="submit">Autorizar</button>
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

