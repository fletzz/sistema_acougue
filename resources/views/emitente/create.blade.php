<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Novo Emitente
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('emitente.store') }}">
                @csrf
                
                <div>
                    <label>CNPJ</label>
                    <input type="text" name="cnpj" required maxlength="14">
                </div>

                <div>
                    <label>Razão Social</label>
                    <input type="text" name="razao_social" required>
                </div>

                <div>
                    <label>Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" required>
                </div>

                <div>
                    <label>Inscrição Estadual</label>
                    <input type="text" name="inscricao_estadual">
                </div>

                <div>
                    <label>CRT</label>
                    <input type="text" name="crt" required maxlength="1">
                </div>

                <div>
                    <label>Logradouro</label>
                    <input type="text" name="logradouro" required>
                </div>

                <div>
                    <label>Número</label>
                    <input type="text" name="numero" required>
                </div>

                <div>
                    <label>Bairro</label>
                    <input type="text" name="bairro" required>
                </div>

                <div>
                    <label>Código Município</label>
                    <input type="number" name="codigo_municipio" required>
                </div>

                <div>
                    <label>Município</label>
                    <input type="text" name="municipio" required>
                </div>

                <div>
                    <label>UF</label>
                    <input type="text" name="uf" required maxlength="2">
                </div>

                <div>
                    <label>CEP</label>
                    <input type="text" name="cep" required maxlength="8">
                </div>

                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>
</x-app-layout>

