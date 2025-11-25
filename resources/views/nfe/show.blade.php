<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            NFe #{{ $notaFiscal->numero }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('nfe.index') }}" style="margin-right: 10px; color: #2563eb; text-decoration: underline;">← Voltar para NFe</a>
                <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: underline;">Dashboard</a>
            </div>
            
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Informações da Nota Fiscal</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><strong>Número:</strong> {{ $notaFiscal->numero }}</p>
                        <p><strong>Série:</strong> {{ $notaFiscal->serie }}</p>
                        <p><strong>Modelo:</strong> {{ $notaFiscal->modelo }}</p>
                        <p><strong>Status:</strong> 
                            <span class="px-2 py-1 rounded text-sm 
                                @if($notaFiscal->status == 'digitacao') bg-yellow-100 text-yellow-800
                                @elseif($notaFiscal->status == 'pronto_envio') bg-blue-100 text-blue-800
                                @elseif($notaFiscal->status == 'autorizada') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($notaFiscal->status == 'digitacao') Em Digitação
                                @elseif($notaFiscal->status == 'pronto_envio') Pronto para Envio à SEFAZ
                                @elseif($notaFiscal->status == 'autorizada') Autorizada
                                @else {{ $notaFiscal->status }}
                                @endif
                            </span>
                        </p>
                    </div>
                    <div>
                        <p><strong>Data de Emissão:</strong> {{ $notaFiscal->data_emissao->format('d/m/Y H:i') }}</p>
                        <p><strong>Ambiente:</strong> {{ $notaFiscal->ambiente == '1' ? 'Produção' : 'Homologação' }}</p>
                        <p><strong>Natureza da Operação:</strong> {{ $notaFiscal->natureza_operacao }}</p>
                        <p><strong>Chave de Acesso:</strong> 
                            <span class="font-mono text-xs">{{ $notaFiscal->chave_acesso }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Emitente</h3>
                <p><strong>Razão Social:</strong> {{ $notaFiscal->emitente->razao_social }}</p>
                <p><strong>CNPJ:</strong> {{ $notaFiscal->emitente->cnpj }}</p>
                <p><strong>Inscrição Estadual:</strong> {{ $notaFiscal->emitente->inscricao_estadual }}</p>
                <p><strong>Endereço:</strong> {{ $notaFiscal->emitente->logradouro }}, {{ $notaFiscal->emitente->numero }} - {{ $notaFiscal->emitente->bairro }}, {{ $notaFiscal->emitente->municipio }}/{{ $notaFiscal->emitente->uf }}</p>
            </div>

            @if($notaFiscal->destinatario)
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Destinatário</h3>
                <p><strong>Nome:</strong> {{ $notaFiscal->destinatario->nome }}</p>
                <p><strong>CPF/CNPJ:</strong> {{ $notaFiscal->destinatario->cpf_cnpj }}</p>
                @if($notaFiscal->destinatario->inscricao_estadual)
                <p><strong>Inscrição Estadual:</strong> {{ $notaFiscal->destinatario->inscricao_estadual }}</p>
                @endif
                @if($notaFiscal->destinatario->logradouro)
                <p><strong>Endereço:</strong> {{ $notaFiscal->destinatario->logradouro }}, {{ $notaFiscal->destinatario->numero ?? 'S/N' }} - {{ $notaFiscal->destinatario->bairro ?? '' }}, {{ $notaFiscal->destinatario->municipio ?? '' }}/{{ $notaFiscal->destinatario->uf ?? '' }}</p>
                @endif
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Itens da Nota</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NCM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CFOP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor Unitário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($notaFiscal->itens as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->descricao }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->ncm }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->cfop }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->unidade }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->quantidade, 3, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Totais</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><strong>Valor dos Produtos:</strong> R$ {{ number_format($notaFiscal->valor_total_produtos, 2, ',', '.') }}</p>
                        <p><strong>Valor do Frete:</strong> R$ {{ number_format($notaFiscal->valor_frete, 2, ',', '.') }}</p>
                        <p><strong>Valor do Desconto:</strong> R$ {{ number_format($notaFiscal->valor_desconto, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p><strong>Valor dos Impostos:</strong> R$ {{ number_format($notaFiscal->valor_impostos, 2, ',', '.') }}</p>
                        <p class="text-lg font-bold"><strong>Valor Total da NF-e:</strong> R$ {{ number_format($notaFiscal->valor_total_nfe, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Ações</h3>
                <div class="flex gap-4">
                    @if($notaFiscal->status == 'digitacao')
                    <form action="{{ route('nfe.autorizar', $notaFiscal->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Validar e Preparar para Envio
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('nfe.xml', $notaFiscal->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
                        Download XML
                    </a>
                </div>
                @if($notaFiscal->status == 'pronto_envio')
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-blue-800"><strong>Nota Fiscal pronta para envio!</strong></p>
                    <p class="text-sm text-blue-600 mt-2">O XML foi gerado e validado. Para enviar à SEFAZ, é necessário:</p>
                    <ul class="list-disc list-inside text-sm text-blue-600 mt-2">
                        <li>Assinar o XML com certificado digital (.p12/.pfx)</li>
                        <li>Enviar para a SEFAZ via webservice SOAP</li>
                        <li>Processar o retorno e salvar o protocolo de autorização</li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

