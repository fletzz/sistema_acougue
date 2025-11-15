<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transformações de Produtos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 24px; font-weight: bold;">Transformações</h2>
                <div>
                    <a href="{{ route('dashboard') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">Dashboard</a>
                    <a href="{{ route('transformacao.create') }}" style="padding: 10px 20px; background: #ea580c; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Nova Transformação</a>
                </div>
            </div>

            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 10px; text-align: left;">ID</th>
                        <th style="padding: 10px; text-align: left;">Produto Origem</th>
                        <th style="padding: 10px; text-align: left;">Quantidade</th>
                        <th style="padding: 10px; text-align: left;">Data</th>
                        <th style="padding: 10px; text-align: left;">Usuário</th>
                        <th style="padding: 10px; text-align: left;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transformacoes as $transformacao)
                    <tr>
                        <td style="padding: 10px;">#{{ $transformacao->id }}</td>
                        <td style="padding: 10px;">{{ $transformacao->produtoOrigem->nome }}</td>
                        <td style="padding: 10px;">{{ number_format($transformacao->quantidade_origem, 3, ',', '.') }} {{ $transformacao->produtoOrigem->unidade_medida }}</td>
                        <td style="padding: 10px;">{{ $transformacao->data_transformacao->format('d/m/Y H:i') }}</td>
                        <td style="padding: 10px;">{{ $transformacao->usuario->name }}</td>
                        <td style="padding: 10px;">
                            <a href="{{ route('transformacao.show', $transformacao->id) }}" style="color: #2563eb; text-decoration: underline;">Ver Detalhes</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

