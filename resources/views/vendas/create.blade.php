<x-pdv-layout>

<!-- HEADER -->
<header class="w-full px-6 py-4 bg-white shadow-sm border-b border-gray-200 flex justify-between items-center">
    <h1 class="text-xl font-semibold">PDV - Frente de Caixa</h1>

    <div class="flex items-center gap-6">
        <div class="text-right">
            <p class="text-xs text-gray-500">Caixa atual</p>
            <p class="text-lg font-semibold">#{{ $caixa->id }}</p>
        </div>

        <a href="{{ route('checkout') }}"
           class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md font-semibold text-sm">
            Sair do PDV
        </a>
    </div>
</header>


<!-- ÁREA PRINCIPAL -->
<div class="grid grid-cols-[1fr_420px] h-[calc(100vh-90px)]">

    <!-- ESQUERDA -->
    <section class="p-6 relative">

        <!-- BUSCA -->
        <div class="mb-6 relative">
            <input 
                id="codigoInput"
                type="text"
                placeholder="Bipar código de barras ou digitar nome..."
                class="w-full h-14 rounded-lg border border-gray-300 px-4 text-lg focus:ring-2 focus:ring-indigo-500"
                autofocus
            >

            <!-- AUTOCOMPLETE DROPDOWN -->
            <div id="autocompleteLista"
                 class="absolute top-14 left-0 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden z-50 max-h-64 overflow-y-auto">
            </div>
        </div>

        <h2 class="text-lg font-semibold mb-3">Itens da Venda</h2>

        <!-- LISTA DE ITENS -->
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <!-- Cabeçalho -->
            <div class="grid grid-cols-6 px-4 py-3 bg-gray-100 text-sm font-semibold text-gray-600 border-b">
                <span>Cód</span>
                <span>Descrição</span>
                <span class="text-center">Unid.</span>
                <span class="text-right">Valor Unit.</span>
                <span class="text-center">Qtd</span>
                <span class="text-right">Subtotal</span>
            </div>

            <!-- Corpo -->
            <div id="listaItensVenda" class="max-h-[calc(100vh-260px)] overflow-y-auto">
                <p class="text-gray-400 text-center p-6">Nenhum item adicionado.</p>
            </div>

        </div>

    </section>


    <!-- DIREITA - CARRINHO -->
    <aside class="bg-gray-50 border-l p-6 flex flex-col">

        <!-- TOTAL -->
        <div class="mt-2 bg-white border rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Subtotal</p>
            <p id="totalCompra" class="text-2xl font-bold text-indigo-600">R$ 0,00</p>
        </div>

        <!-- FORMAS DE PAGAMENTO -->
        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Forma de Pagamento</h3>

<div class="grid grid-cols-2 gap-4 mt-6">

    <button class="pagamento-btn" onclick="selecionarPagamento(1)">
        <x-icon.dinheiro class="w-7 h-7 text-gray-700 opacity-70" />
        <span class="font-semibold text-sm mt-1">Dinheiro</span>
    </button>

    <button class="pagamento-btn" onclick="selecionarPagamento(2)">
        <x-icon.cartao class="w-7 h-7 text-gray-700 opacity-70" />
        <span class="font-semibold text-sm mt-1">Cartão</span>
    </button>

    <button class="pagamento-btn" onclick="selecionarPagamento(3)">
        <x-icon.pix class="w-8 h-8 text-[#32BCAD]" />
        <span class="font-semibold text-sm mt-1">PIX</span>
    </button>

    <button class="pagamento-btn" onclick="selecionarPagamento(4)">
        <x-icon.fiado class="w-7 h-7 text-gray-700 opacity-70" />
        <span class="font-semibold text-sm mt-1">Fiado</span>
    </button>

</div>

        </div>

        <!-- FINALIZAR -->
        <button 
            id="btnFinalizar"
            class="w-full h-14 mt-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-lg">
            Finalizar Venda
        </button>

    </aside>

</div>



<style>
.pagamento-btn {
    @apply flex flex-col items-center justify-center gap-2
           h-28 rounded-xl border border-gray-300 bg-white 
           shadow-sm hover:bg-gray-100 transition cursor-pointer;
}
.pagamento-btn.active {
    @apply border-indigo-600 bg-indigo-50;
}
</style>




<script>
let carrinho = [];
let pagamentoSelecionado = null;

// ----------------------- PAGAMENTO --------------------------
function selecionarPagamento(id) {
    pagamentoSelecionado = id;

    document.querySelectorAll(".pagamento-btn")
        .forEach(btn => btn.classList.remove("active"));

    event.currentTarget.classList.add("active");
}



// ----------------------- AUTOCOMPLETE PROFISSIONAL --------------------------
document.getElementById("codigoInput").addEventListener("input", async function () {
    const busca = this.value.trim();
    const box = document.getElementById("autocompleteLista");

    if (busca.length < 1) {
        box.classList.add("hidden");
        return;
    }

    const res = await fetch(`/pdv/produtos?q=${busca}`);
    const produtos = await res.json();

    if (produtos.length === 0) {
        box.innerHTML = `<p class="p-3 text-gray-500">Nenhum produto encontrado.</p>`;
        box.classList.remove("hidden");
        return;
    }

    box.innerHTML = produtos.map(p => `
        <div onclick="selecionarProduto(${p.id}, '${p.nome}', ${p.preco_venda}, '${p.unidade_medida ?? "UN"}')"
             class="p-3 hover:bg-gray-100 cursor-pointer border-b last:border-none">
             
            <p class="font-medium">${p.nome}</p>
            <p class="text-sm text-gray-500">R$ ${Number(p.preco_venda).toFixed(2)} • Unidade: ${p.unidade_medida}</p>
        </div>
    `).join('');

    box.classList.remove("hidden");
});


function selecionarProduto(id, nome, preco, unidade) {
    addCarrinho(id, nome, preco, unidade);
    document.getElementById("autocompleteLista").classList.add("hidden");
    document.getElementById("codigoInput").value = "";
}



// ----------------------- ADICIONAR AO CARRINHO --------------------------
function addCarrinho(id, nome, preco, unidade) {

    let item = carrinho.find(i => i.id === id);

    if (item) {
        item.qtd++;
    } else {
        carrinho.push({ id, nome, preco, unidade, qtd: 1 });
    }

    renderCarrinho();
}




// ----------------------- CONTROLES --------------------------
function aumentar(id) {
    let item = carrinho.find(i => i.id === id);
    if (item) item.qtd++;
    renderCarrinho();
}

function diminuir(id) {
    let item = carrinho.find(i => i.id === id);
    if (item && item.qtd > 1) item.qtd--;
    else remover(id);
    renderCarrinho();
}

function remover(id) {
    carrinho = carrinho.filter(i => i.id !== id);
    renderCarrinho();
}



// ----------------------- RENDERIZAÇÃO DA TABELA --------------------------
function renderCarrinho() {
    const lista = document.getElementById("listaItensVenda");

    if (carrinho.length === 0) {
        lista.innerHTML = `<p class="text-gray-400 text-center p-6">Nenhum item adicionado.</p>`;
        document.getElementById("totalCompra").innerHTML = "R$ 0,00";
        return;
    }

    lista.innerHTML = carrinho.map(item => `
        <div class="grid grid-cols-6 items-center px-4 py-3 border-b text-sm">

            <span>${item.id}</span>

            <span class="font-semibold">${item.nome}</span>

            <span class="text-center">${item.unidade}</span>

            <span class="text-right">R$ ${item.preco.toFixed(2)}</span>

            <div class="flex items-center justify-center gap-2">
                <button onclick="diminuir(${item.id})"
                    class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-md hover:bg-gray-300 font-bold">
                    −
                </button>

                <span>${item.qtd}</span>

                <button onclick="aumentar(${item.id})"
                    class="w-6 h-6 flex items-center justify-center bg-indigo-200 rounded-md hover:bg-indigo-300 font-bold text-indigo-700">
                    +
                </button>
            </div>

            <span class="text-right font-semibold">
                R$ ${(item.preco * item.qtd).toFixed(2)}
            </span>

        </div>
    `).join('');

    let total = carrinho.reduce((t, i) => t + (i.preco * i.qtd), 0);
    document.getElementById("totalCompra").innerHTML = "R$ " + total.toFixed(2);
}



// ----------------------- FINALIZAR VENDA --------------------------
document.getElementById("btnFinalizar").addEventListener("click", async function () {

    if (carrinho.length === 0) {
        alert("Carrinho vazio.");
        return;
    }

    if (!pagamentoSelecionado) {
        alert("Selecione uma forma de pagamento.");
        return;
    }

    let total = carrinho.reduce((t, i) => t + i.preco * i.qtd, 0);

    const formData = new FormData();
    formData.append("cliente_id", "");
    formData.append("forma_pagamento_id", pagamentoSelecionado);
    formData.append("valor_total_final", total);
    formData.append("items", JSON.stringify(
        carrinho.map(i => ({
            produto_id: i.id,
            quantidade: i.qtd,
            preco_unitario: i.preco
        }))
    ));

    const res = await fetch("/vendas", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    });

    if (!res.ok) {
        alert("Erro ao finalizar venda!");
        return;
    }

    alert("Venda finalizada!");
    carrinho = [];
    renderCarrinho();
});

</script>

</x-pdv-layout>
