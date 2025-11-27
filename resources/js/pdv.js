let carrinho = [];

document.getElementById('codigoInput').addEventListener('input', function () {
    let termo = this.value;

    fetch(`/api/produtos/buscar?termo=${termo}`)
        .then(res => res.json())
        .then(lista => {
            let html = '';

            lista.forEach(p => {
                html += `
                    <div class="py-2 px-3 border-b flex justify-between items-center">
                        <div>
                            <p class="font-semibold">${p.nome}</p>
                            <small class="text-gray-500">R$ ${p.preco_venda}</small>
                        </div>

                        <button 
                            class="bg-indigo-600 text-white px-3 py-1 rounded"
                            onclick="adicionarCarrinho(${p.id}, '${p.nome}', ${p.preco_venda})">
                            Adicionar
                        </button>
                    </div>
                `;
            });

            document.getElementById('resultadoBusca').innerHTML = html;
        });
});

window.adicionarCarrinho = function (id, nome, preco) {
    carrinho.push({ id, nome, preco });

    atualizarCarrinho();
};

function atualizarCarrinho() {
    let total = 0;
    let html = '';

    carrinho.forEach(item => {
        total += item.preco;
        html += `
            <div class="py-2 border-b">
                <p class="font-semibold">${item.nome}</p>
                <p class="text-sm">R$ ${item.preco.toFixed(2)}</p>
            </div>
        `;
    });

    document.getElementById('carrinho').innerHTML = html;
    document.getElementById('totalGeral').innerText = 
        "R$ " + total.toFixed(2);
}
