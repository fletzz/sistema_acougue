<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frente de Caixa (PDV)</title>
    <style>
        body { font-family: sans-serif; }
        .pdv-container { display: flex; }
        .catalogo { width: 40%; padding: 10px; border-right: 1px solid #ccc; }
        .venda { width: 60%; padding: 10px; }
        .produto-item { cursor: pointer; padding: 5px; border-bottom: 1px solid #eee; }
        .produto-item:hover { background-color: #f0f0f0; }
        #carrinho-items { min-height: 150px; border: 1px solid #ddd; padding: 5px; }
        .carrinho-item { display: flex; justify-content: space-between; }
        .total { font-size: 24px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

@if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
        <strong>Ops! Algo deu errado:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="/vendas" method="POST" id="form-venda">
        @csrf

        <div class="pdv-container">

            <div class="catalogo">
                <h3>Catálogo de Produtos</h3>
                <input type="text" id="busca-produto" placeholder="Buscar produto..." style="width: 95%;">
                
                <div id="catalogo-produtos">
                    @foreach ($produtos as $produto)
                        <div class="produto-item" 
                             onclick="adicionarAoCarrinho({{ $produto->id }}, '{{ $produto->nome }}', {{ $produto->preco_venda }})">
                            {{ $produto->nome }} (R$ {{ $produto->preco_venda }})
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="venda">
                <h3>Informações da Venda</h3>

                <div>
                    <label for="cliente_id">Cliente (Opcional, para "Fiado"):</label>
                    <select id="cliente_id" name="cliente_id">
                        <option value="">Consumidor Final (Padrão)</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <h4>Carrinho</h4>
                <div id="carrinho-items">
                    </div>
                <div class="total">
                    Total: R$ <span id="total-venda">0.00</span>
                </div>

                <hr>

                <div>
                    <label for="forma_pagamento_id">Forma de Pagamento:</label>
                    <select id="forma_pagamento_id" name="forma_pagamento_id">
                        @foreach ($formaPagamentos as $forma)
                            <option value="{{ $forma->id }}">{{ $forma->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                
                <input type="hidden" name="items" id="hidden-items">
                <input type="hidden" name="valor_total_final" id="hidden-total">
                
                <button type="submit" style="font-size: 20px; padding: 10px; margin-top: 20px;">
                    Finalizar Venda
                </button>
            </div>

        </div>
    </form>


    <script>
        // Nosso "carrinho" de compras em memória
        let carrinho = [];

        // Função chamada pelo clique no catálogo
        function adicionarAoCarrinho(id, nome, preco) {
            // Pede a quantidade (simples, mas funcional)
            let quantidade = prompt(`Qual a quantidade de "${nome}"?`, 1);
            quantidade = parseFloat(quantidade);

            if (quantidade > 0) {
                // Adiciona o item ao nosso array 'carrinho'
                carrinho.push({
                    produto_id: id,
                    nome: nome,
                    quantidade: quantidade,
                    preco_unitario: preco
                });
                
                // Atualiza a tela
                renderizarCarrinho();
            }
        }

        // Função para atualizar o HTML do carrinho e o total
        function renderizarCarrinho() {
            let carrinhoHTML = '';
            let total = 0;
            
            // Loop sobre os itens no array 'carrinho'
            carrinho.forEach((item, index) => {
                let subtotalItem = item.quantidade * item.preco_unitario;
                total += subtotalItem;

                carrinhoHTML += `
                    <div class="carrinho-item">
                        <span>${item.quantidade}x ${item.nome} (R$ ${item.preco_unitario.toFixed(2)})</span>
                        <span>R$ ${subtotalItem.toFixed(2)}</span>
                        <button type="button" onclick="removerDoCarrinho(${index})">X</button>
                    </div>
                `;
            });

            // Atualiza o HTML
            document.getElementById('carrinho-items').innerHTML = carrinhoHTML;
            document.getElementById('total-venda').innerText = total.toFixed(2);
        }

        // Função chamada pelo botão "X" de remover
        function removerDoCarrinho(index) {
            // Remove o item do array pela sua posição (index)
            carrinho.splice(index, 1);
            // Atualiza a tela
            renderizarCarrinho();
        }

        // Filtro de busca simples para o catálogo
        document.getElementById('busca-produto').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            let items = document.querySelectorAll('#catalogo-produtos .produto-item');
            
            items.forEach(item => {
                if (item.innerText.toLowerCase().includes(filtro)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // ANTES DE ENVIAR O FORMULÁRIO (O MAIS IMPORTANTE)
        document.getElementById('form-venda').addEventListener('submit', function(event) {
            
            // 1. Pega os dados do 'carrinho' (JavaScript)
            // 2. Converte para JSON (uma string)
            // 3. Coloca essa string no campo escondido 'hidden-items'
            document.getElementById('hidden-items').value = JSON.stringify(carrinho);

            // 4. Pega o total e coloca no campo escondido 'hidden-total'
            let total = document.getElementById('total-venda').innerText;
            document.getElementById('hidden-total').value = total;

            // Agora o formulário é enviado para o backend (store())
            // com 'cliente_id', 'forma_pagamento_id', 'items' (JSON) e 'valor_total_final'
        });

    </script>
</body>
</html>