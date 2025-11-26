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
                <div class="bg-white rounded-xl shadow border border-gray-100 px-6 py-4 flex items-center justify-between">

                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Total de Produtos</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">
                            {{ $totalProdutos }}
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center">
                        <x-icon.caixa class="text-blue-500 h-6 w-6" />
                    </div>
                </div>

                {{-- Estoque baixo --}}
                <div class="bg-white rounded-xl shadow border border-gray-100 px-6 py-4 flex items-center justify-between">

                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Estoque Baixo</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">
                            {{ $estoqueBaixo }}
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                        <x-icon.alerta class="text-red-500 h-6 w-6" />
                    </div>

                </div>

                {{-- Valor total hoje --}}
                <div class="bg-white rounded-xl shadow border border-gray-100 px-6 py-4 flex items-center justify-between">

                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Valor total hoje</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">
                            R$ {{ number_format($valorTotalHoje, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <x-icon.cifrao class="text-green-500 h-6 w-6" />
                    </div>

                </div>

                {{-- Fiados a receber --}}
                <div class="bg-white rounded-xl shadow border border-gray-100 px-6 py-4 flex items-center justify-between">

                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Fiados a receber</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">
                            R$ {{ number_format($fiadosAReceber, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-full bg-blue-200 flex items-center justify-center">
                        <div class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm">
                            <x-icon.fiados :count="$fiadosAReceber" />
                        </div>
                    </div>

                </div>

            </div>

                        {{-- Linha com gráfico + fiados próximos --}}
            <div class="grid grid-cols-3 gap-5 mb-10">

                {{-- Gráfico Premium --}}
                <div class="col-span-2 bg-white rounded-2xl shadow-md border border-gray-100 px-8 py-6">

                    <div class="flex items-center justify-between mb-5">
                        <p class="text-[15px] font-semibold text-gray-700">Vendas últimos 7 dias</p>

                        <div class="flex items-center gap-2 text-[13px] text-gray-500">
                            <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
                            <span>Lucros</span>
                        </div>
                    </div>

                    {{-- container do gráfico --}}
                    <div class="mt-4 h-64 w-full">

                        <svg viewBox="0 0 400 180" class="w-full h-full">

                            <!-- Linhas horizontais suaves -->
                            @php
                                $lines = [40, 70, 100, 130, 160];
                            @endphp
                            @foreach ($lines as $y)
                                <line x1="40" y1="{{ $y }}" x2="380" y2="{{ $y }}"
                                    stroke="#F1F3F4" stroke-width="1" />
                            @endforeach

                            <!-- linha -->
                            <polyline
                                fill="none"
                                stroke="#FACC15"
                                stroke-width="3"
                                points="40,140 90,115 140,130 190,100 240,90 290,80 340,70"
                            />

                            <!-- Bolinhas -->
                            @php
                                $points = [
                                    [40,140], [90,115], [140,130], [190,100],
                                    [240,90], [290,80], [340,70]
                                ];
                            @endphp
                            @foreach ($points as [$x, $y])
                                <circle cx="{{ $x }}" cy="{{ $y }}" r="5"
                                        fill="#FACC15" stroke="white" stroke-width="2"/>
                            @endforeach

                            <!-- labels eixo X -->
                            @php
                                $dias = ['Seg','Ter','Qua','Qui','Sex','Sab','Dom'];
                                $xs   = [40,90,140,190,240,290,340];
                            @endphp
                            @foreach ($dias as $i => $dia)
                                <text x="{{ $xs[$i] }}" y="172" font-size="12"
                                    text-anchor="middle" fill="#9CA3AF">
                                    {{ $dia }}
                                </text>
                            @endforeach

                        </svg>

                    </div>

                </div>

                {{-- Fiados próximos (Premium Table) --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 px-7 py-6">

                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[15px] font-semibold text-gray-700">Fiados próximos</p>
                        <a href="#" class="text-[13px] text-blue-600 font-semibold hover:underline">
                            Ver todos
                        </a>
                    </div>

                    {{-- Cabeçalho da tabela --}}
                    <div class="grid grid-cols-3 text-[12px] font-semibold text-gray-400 border-b border-gray-100 pb-2 mb-3">
                        <span>Cliente</span>
                        <span>Valor</span>
                        <span class="text-right">Data venc.</span>
                    </div>

                    {{-- Linhas --}}
                    <div class="space-y-3 text-[14px] text-gray-700">

                        @forelse ($fiadosProximos as $fiado)
                            <div class="grid grid-cols-3 py-1.5 border-b border-gray-50">
                                <span>{{ $fiado->cliente->nome ?? 'Cliente' }}</span>

                                <span class="font-medium">
                                    R$ {{ number_format($fiado->valor_pago, 2, ',', '.') }}
                                </span>

                                <span class="text-right text-gray-600">
                                    {{ \Carbon\Carbon::parse($fiado->data_pagamento)->format('d/m/Y') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-[13px] text-gray-400">Nenhum fiado pendente.</p>
                        @endforelse

                    </div>
                </div>
            </div>


            {{-- Última linha: ações rápidas + alerta de estoque --}}
            <div class="grid grid-cols-3 gap-5">

                {{-- Ações rápidas Premium --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 px-7 py-6">

                    <p class="text-[15px] font-semibold text-gray-700 mb-4">Ações rápidas</p>

                    <div class="space-y-4 text-[14px]">

            {{-- Novo produto --}}
            <div class="flex items-center gap-3 px-2 py-1.5 rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                <span class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center">
                    <x-icon.caixa class="text-blue-600 h-5 w-5" />
                </span>
                <span class="text-gray-700">Novo produto</span>
            </div>

            {{-- Editar produto --}}
            <div class="flex items-center gap-3 px-2 py-1.5 rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                <span class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center">
                    <x-icon.editar class="text-green-600 h-5 w-5" />
                </span>
                <span class="text-gray-700">Editar produto</span>
            </div>

            {{-- Novo fornecedor --}}
            <div class="flex items-center gap-3 px-2 py-1.5 rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                <span class="h-9 w-9 rounded-full bg-purple-100 flex items-center justify-center">
                    <x-icon.fornecedor class="text-purple-600 h-5 w-5" />
                </span>
                <span class="text-gray-700">Novo fornecedor</span>
            </div>

            {{-- Entrada --}}
            <div class="flex items-center gap-3 px-2 py-1.5 rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                <span class="h-9 w-9 rounded-full bg-orange-100 flex items-center justify-center">
                    <x-icon.entrada class="text-orange-600 h-5 w-5" />
                </span>
                <span class="text-gray-700">Entrada</span>
            </div>

                    </div>

                </div>

                {{-- Alerta de estoque Premium --}}
                <div class="col-span-2 bg-white rounded-2xl shadow-md border border-gray-100 px-7 py-6">

                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[15px] font-semibold text-gray-700">Alerta de estoque</p>

                        <a href="#" class="text-[13px] text-blue-600 font-semibold hover:underline">
                            Ver todos
                        </a>
                    </div>

                    {{-- Cabeçalho tabela Premium --}}
                    <div class="grid grid-cols-6 text-[12px] font-semibold text-gray-400 border-b border-gray-100 pb-2 mb-3">
                        <span class="col-span-2">Produto</span>
                        <span>Código</span>
                        <span class="text-center">Estoque mínimo</span>
                        <span class="text-center">Estoque atual</span>
                        <span class="text-right">Status</span>
                    </div>

                    <div class="space-y-3 text-[14px] text-gray-700">

                        @foreach ($alertaEstoque as $produto)
                            @php
                                $diff = $produto->estoque_atual - $produto->estoque_minimo;

                                if ($produto->estoque_atual <= 0 || $diff < 0) {
                                    $statusLabel = 'Crítico';
                                    $statusClass = 'bg-red-100 text-red-700';
                                } elseif ($diff <= $produto->estoque_minimo * 0.2) {
                                    $statusLabel = 'Médio';
                                    $statusClass = 'bg-yellow-100 text-yellow-700';
                                } else {
                                    $statusLabel = 'Estável';
                                    $statusClass = 'bg-green-100 text-green-700';
                                }
                            @endphp

                            <div class="grid grid-cols-6 items-center py-2 border-b border-gray-50">

                                {{-- Produto + Código de barras menor --}}
                                <div class="col-span-2 flex flex-col">
                                    <span class="font-medium">{{ $produto->nome }}</span>
                                    <span class="text-[12px] text-gray-400">{{ $produto->codigo_barras }}</span>
                                </div>

                                {{-- Código --}}
                                <span class="text-gray-700">{{ $produto->id }}</span>

                                {{-- Estoque mínimo --}}
                                <span class="text-center text-gray-700">
                                    {{ $produto->estoque_minimo }} unidades
                                </span>

                                {{-- Estoque atual --}}
                                <span class="text-center text-gray-700">
                                    {{ $produto->estoque_atual }} unidades
                                </span>

                                {{-- Status Premium --}}
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-full text-[12px] font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                        @endforeach

                        {{-- Nenhum produto --}}
                        @if ($alertaEstoque->isEmpty())
                            <p class="text-[13px] text-gray-400 mt-2">Nenhum produto em alerta de estoque.</p>
                        @endif

                    </div>

                </div>

            </div>
        </main>
    </div>
</x-app-layout>
