<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Emitente
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('emitente.update', $emitente->id) }}">
                @csrf
                @method('PUT')
                
                <div>
                    <label>CNPJ</label>
                    <input type="text" name="cnpj" value="{{ $emitente->cnpj }}" required maxlength="14">
                </div>

                <div>
                    <label>Razão Social</label>
                    <input type="text" name="razao_social" value="{{ $emitente->razao_social }}" required>
                </div>

                <div>
                    <label>Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" value="{{ $emitente->nome_fantasia }}" required>
                </div>

                <div>
                    <label>Inscrição Estadual</label>
                    <input type="text" name="inscricao_estadual" value="{{ $emitente->inscricao_estadual }}">
                </div>

                <div>
                    <label>CRT</label>
                    <input type="text" name="crt" value="{{ $emitente->crt }}" required maxlength="1">
                </div>

                <div>
                    <label>Logradouro</label>
                    <input type="text" name="logradouro" value="{{ $emitente->logradouro }}" required>
                </div>

                <div>
                    <label>Número</label>
                    <input type="text" name="numero" value="{{ $emitente->numero }}" required>
                </div>

                <div>
                    <label>Bairro</label>
                    <input type="text" name="bairro" value="{{ $emitente->bairro }}" required>
                </div>

                <div>
                    <label>Código Município</label>
                    <input type="number" name="codigo_municipio" value="{{ $emitente->codigo_municipio }}" required>
                </div>

                <div>
                    <label>Município</label>
                    <input type="text" name="municipio" value="{{ $emitente->municipio }}" required>
                </div>

                <div>
                    <label>UF</label>
                    <input type="text" name="uf" value="{{ $emitente->uf }}" required maxlength="2">
                </div>

                <div>
                    <label>CEP</label>
                    <input type="text" name="cep" value="{{ $emitente->cep }}" required maxlength="8">
                </div>

                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>
</x-app-layout>

