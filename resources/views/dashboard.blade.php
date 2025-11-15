<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Vendas Hoje</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #059669;">R$ {{ number_format($vendasHoje, 2, ',', '.') }}</p>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Vendas do Mês</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #2563eb;">R$ {{ number_format($vendasMes, 2, ',', '.') }}</p>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Total de Vendas</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #7c3aed;">{{ $totalVendas }}</p>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Clientes</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #dc2626;">{{ $totalClientes }}</p>
                    <a href="{{ route('clientes.index') }}" style="color: #2563eb; font-size: 12px; text-decoration: underline;">Ver todos</a>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Produtos</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #ea580c;">{{ $totalProdutos }}</p>
                    <a href="{{ route('produtos.index') }}" style="color: #2563eb; font-size: 12px; text-decoration: underline;">Ver todos</a>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Caixas Abertos</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #16a34a;">{{ $caixasAbertos }}</p>
                    <a href="{{ route('caixa.index') }}" style="color: #2563eb; font-size: 12px; text-decoration: underline;">Gerenciar</a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Ações Rápidas
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="{{ route('vendas.create') }}" style="display: block; padding: 12px; background: #059669; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: bold;">
                            Nova Venda
                        </a>
                        <a href="{{ route('produtos.create') }}" style="display: block; padding: 12px; background: #2563eb; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: bold;">
                            Novo Produto
                        </a>
                        <a href="{{ route('clientes.create') }}" style="display: block; padding: 12px; background: #7c3aed; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: bold;">
                            Novo Cliente
                        </a>
                        <a href="{{ route('entrada_mercadoria.create') }}" style="display: block; padding: 12px; background: #059669; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: bold;">
                            Entrada de Bovinos
                        </a>
                        <a href="{{ route('transformacao.create') }}" style="display: block; padding: 12px; background: #dc2626; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: bold;">
                            Transformar Produto (Cortar Carne)
                        </a>
                    </div>
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Alertas
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @if($contasPendentes > 0)
                        <div style="padding: 12px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                            <strong>Contas a Receber:</strong> R$ {{ number_format($contasPendentes, 2, ',', '.') }}
                            <a href="{{ route('contas_receber.index') }}" style="display: block; margin-top: 5px; color: #2563eb; font-size: 12px;">Ver detalhes</a>
                        </div>
                        @endif

                        @if($produtosEstoqueBaixo > 0)
                        <div style="padding: 12px; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 4px;">
                            <strong>Estoque Baixo:</strong> {{ $produtosEstoqueBaixo }} produto(s)
                            <a href="{{ route('produtos.index') }}" style="display: block; margin-top: 5px; color: #2563eb; font-size: 12px;">Ver produtos</a>
                        </div>
                        @endif

                        @if($nfePendentes > 0)
                        <div style="padding: 12px; background: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 4px;">
                            <strong>NFe Pendentes:</strong> {{ $nfePendentes }} nota(s)
                            <a href="{{ route('nfe.index') }}" style="display: block; margin-top: 5px; color: #2563eb; font-size: 12px;">Ver NFe</a>
                        </div>
                        @endif

                        @if($contasPendentes == 0 && $produtosEstoqueBaixo == 0 && $nfePendentes == 0)
                        <p style="color: #059669; padding: 12px;">✓ Tudo em ordem!</p>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Vendas Recentes
                    </h3>
                    @if($vendasRecentes->count() > 0)
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb;">ID</th>
                                <th style="padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb;">Cliente</th>
                                <th style="padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb;">Valor</th>
                                <th style="padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendasRecentes as $venda)
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">#{{ $venda->id }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">{{ $venda->cliente->nome ?? 'Consumidor Final' }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">R$ {{ number_format($venda->valor_total_final, 2, ',', '.') }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                    <a href="{{ route('vendas.show', $venda->id) }}" style="color: #2563eb; text-decoration: underline;">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="{{ route('vendas.index') }}" style="color: #2563eb; text-decoration: underline;">Ver todas as vendas</a>
                    </div>
                    @else
                    <p style="color: #6b7280; padding: 20px; text-align: center;">Nenhuma venda ainda</p>
                    @endif
                </div>

                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Produtos Mais Vendidos
                    </h3>
                    @if($produtosMaisVendidos->count() > 0)
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb;">Produto</th>
                                <th style="padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb;">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produtosMaisVendidos as $produto)
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">{{ $produto->nome }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">{{ number_format($produto->total_vendido, 3, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="{{ route('produtos.index') }}" style="color: #2563eb; text-decoration: underline;">Ver todos os produtos</a>
                    </div>
                    @else
                    <p style="color: #6b7280; padding: 20px; text-align: center;">Nenhuma venda ainda</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
