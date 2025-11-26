<x-app-layout>
    <x-slot name="title">
        Boas vendas {{ Auth::user()->name }}!
    </x-slot>
    {{-- Deixa o header padrão vazio --}}
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-[#F5F6FA] flex">

        {{-- CONTEÚDO PRINCIPAL --}}
        <main class="flex-1 px-8 py-6 overflow-y-auto">
            {{-- Cards de resumo --}}
            <div class="grid grid-cols-4 gap-4 mb-6">
                {{-- Total de produtos --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Total de Produtos</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            {{ $totalProdutos }}
                        </p>

                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center">
                        <img src="{{ asset('icons/caixa.svg') }}" alt="" class="h-4 w-4">
                    </div>
                </div>

                {{-- Estoque baixo --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Estoque Baixo</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            {{ $estoqueBaixo }}
                        </p>

                    </div>
                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center">
                        <img src="{{ asset('icons/alerta.svg') }}" alt="" class="h-4 w-4">
                    </div>
                </div>

                {{-- Valor total hoje --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Valor total hoje</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            R$ {{ number_format($valorTotalHoje, 2, ',', '.') }}
                        </p>

                    </div>
                    <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center">
                        <img src="{{ asset('icons/cifrao.svg') }}" alt="" class="h-4 w-4">
                    </div>
                </div>

                {{-- Fiados a receber --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Fiados a receber</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            R$ {{ number_format($fiadosAReceber, 2, ',', '.') }}
                        </p>

                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center">
                        <div class="h-6 w-6 rounded-full bg-blue-400 flex items-center justify-center text-white text-sm">
                            5
                        </div>
                    </div>
                </div>
            </div>

            {{-- Linha com gráfico + fiados próximos --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                {{-- Gráfico --}}
                <div class="col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-700">Vendas últimos 7 dias</p>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                            <span>Lucros</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        {{-- Gráfico estático em SVG só para parecer igual --}}
                        <div class="h-56 w-full">
                            <svg viewBox="0 0 400 160" class="w-full h-full">
                                <!-- eixo -->
                                <line x1="40" y1="10" x2="40" y2="140" stroke="#E5E7EB" stroke-width="1" />
                                <line x1="40" y1="140" x2="380" y2="140" stroke="#E5E7EB" stroke-width="1" />

                                <!-- linha -->
                                <polyline
                                    fill="none"
                                    stroke="#FACC15"
                                    stroke-width="3"
                                    points="40,120 90,95 140,110 190,80 240,70 290,60 340,50"
                                />
                                <!-- bolinhas -->
                                @php
                                    $points = [
                                        [40,120], [90,95], [140,110], [190,80], [240,70], [290,60], [340,50]
                                    ];
                                @endphp
                                @foreach($points as [$x, $y])
                                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#FACC15" stroke="white" stroke-width="2"/>
                                @endforeach

                                <!-- labels eixo X -->
                                @php
                                    $dias = ['Seg','Ter','Qua','Qui','Sex','Sab','Dom'];
                                    $xs   = [40,90,140,190,240,290,340];
                                @endphp
                                @foreach($dias as $i => $dia)
                                    <text x="{{ $xs[$i] }}" y="155" font-size="11" text-anchor="middle" fill="#9CA3AF">
                                        {{ $dia }}
                                    </text>
                                @endforeach
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Fiados próximos --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-gray-700">Fiados próximos</p>
                        <a href="#" class="text-xs text-blue-600 font-semibold">Ver todos</a>
                    </div>

                    <div class="text-xs font-semibold text-gray-400 grid grid-cols-3 mb-2">
                        <span>Cliente</span>
                        <span>Valor</span>
                        <span class="text-right">Data venc.</span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-700">
                        @forelse ($fiadosProximos as $fiado)
                            <div class="grid grid-cols-3">
                                <span>{{ $fiado->cliente->nome ?? 'Cliente' }}</span>

                                <span>
                                    R$ {{ number_format($fiado->valor_pago, 2, ',', '.') }}
                                </span>

                                <span class="text-right">
                                    {{ \Carbon\Carbon::parse($fiado->data_pagamento)->format('d/m/Y') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400">Nenhum fiado pendente.</p>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- Última linha: ações rápidas + alerta de estoque --}}
            <div class="grid grid-cols-3 gap-4">
                {{-- Ações rápidas --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
                    <p class="text-sm font-semibold text-gray-700 mb-4">Ações rápidas</p>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center">
                                <img src="{{ asset('icons/caixa.svg') }}" alt="" class="h-4 w-4">
                            </span>
                            <span>Novo produto</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-full bg-green-50 flex items-center justify-center">
                                <img src="{{ asset('icons/editar.svg') }}" alt="" class="h-4 w-4">
                            </span>
                            <span>Editar produto</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-full bg-purple-50 flex items-center justify-center">
                                <img src="{{ asset('icons/adicionar-cliente.svg') }}" alt="" class="h-4 w-4">
                            </span>
                            <span>Novo fornecedor</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-full bg-orange-50 flex items-center justify-center">
                                <img src="{{ asset('icons/carrinho.svg') }}" alt="" class="h-4 w-4">
                            </span>
                            <span>Entrada</span>
                        </div>
                    </div>
                </div>

                {{-- Alerta de estoque --}}
                <div class="col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-gray-700">Alerta de estoque</p>
                        <a href="#" class="text-xs text-blue-600 font-semibold">Ver todos</a>
                    </div>

                    <div class="grid grid-cols-6 text-xs font-semibold text-gray-400 mb-2">
                        <span class="col-span-2">Produto</span>
                        <span>Código</span>
                        <span class="text-center">Estoque mínimo</span>
                        <span class="text-center">Estoque atual</span>
                        <span class="text-right">Status</span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-700">

                        @foreach ($alertaEstoque as $produto)
                            @php
                                $diff = $produto->estoque_atual - $produto->estoque_minimo;

                                if ($produto->estoque_atual <= 0 || $diff < 0) {
                                    $statusLabel = 'Crítico';
                                    $statusClass = 'bg-red-50 text-red-600';
                                } elseif ($diff <= $produto->estoque_minimo * 0.2) {
                                    $statusLabel = 'Médio';
                                    $statusClass = 'bg-yellow-50 text-yellow-700';
                                } else {
                                    $statusLabel = 'Estável';
                                    $statusClass = 'bg-green-50 text-green-700';
                                }
                            @endphp

                            <div class="grid grid-cols-6 items-center py-1.5 border-t border-gray-100">
                                <div class="col-span-2 flex flex-col">
                                    <span>{{ $produto->nome }}</span>
                                    <span class="text-xs text-gray-400">
                                        {{ $produto->codigo_barras }}
                                    </span>
                                </div>

                                <span>{{ $produto->id }}</span> {{-- ou código interno, ajuste aqui --}}
                                <span class="text-center">
                                    {{ $produto->estoque_minimo }} unidades
                                </span>
                                <span class="text-center">
                                    {{ $produto->estoque_atual }} unidades
                                </span>

                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if ($alertaEstoque->isEmpty())
                            <p class="text-xs text-gray-400 mt-2">Nenhum produto em alerta de estoque.</p>
                        @endif

                    </div>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
