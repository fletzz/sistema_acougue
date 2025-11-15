<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Emitentes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('emitente.create') }}" class="mb-4 inline-block">Novo Emitente</a>
            
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>CNPJ</th>
                        <th>Razão Social</th>
                        <th>Nome Fantasia</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emitentes as $emitente)
                    <tr>
                        <td>{{ $emitente->cnpj }}</td>
                        <td>{{ $emitente->razao_social }}</td>
                        <td>{{ $emitente->nome_fantasia }}</td>
                        <td>
                            <a href="{{ route('emitente.edit', $emitente->id) }}">Editar</a>
                            <form method="POST" action="{{ route('emitente.destroy', $emitente->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

