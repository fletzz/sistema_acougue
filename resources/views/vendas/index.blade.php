<x-app-layout>
    <div class="max-w-6xl mx-auto px-8 py-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-4">Vendas</h1>

        @include('vendas.partials.lista', ['vendasRecentes' => $vendas])
    </div>
</x-app-layout>
