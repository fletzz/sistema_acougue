<x-guest-layout>

    {{-- Cabeçalho com logo e título --}}
    <div class="flex flex-col items-center mb-8">
        {{-- Troque esse src pelo caminho do seu logo (PNG/SVG) --}}
        <img src="{{ asset('images/logo-flamboyant.svg') }}" 
             alt="Flamboyant" 
             class="h-12 mb-3">

        <span class="text-xs tracking-[0.25em] text-gray-500 uppercase mb-1">
        </span>

        <h1 class="text-2xl font-semibold text-gray-800">
            Login
        </h1>
    </div>

    <!-- Status de sessão (mensagens tipo: senha alterada etc.) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Campo de email / usuário --}}
        <div>
            <x-input-label for="email" value="Nome de usuário" />

            <div class="mt-1 relative">
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    class="block w-full pl-4 pr-10 py-2 border-gray-300 rounded-md focus:border-blue-600 focus:ring-blue-600"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Digite seu usuário ou e-mail"
                />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Campo de senha --}}
        <div>
            <x-input-label for="password" value="Senha" />

            <div class="mt-1 relative">
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    class="block w-full pl-4 pr-10 py-2 border-gray-300 rounded-md focus:border-blue-600 focus:ring-blue-600"
                    required
                    autocomplete="current-password"
                    placeholder="Digite sua senha"
                />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Lembrar e link "Esqueci minha senha" --}}
        <div class="flex items-center justify-between text-sm mt-1">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                >
                <span class="text-gray-700">
                    Lembrar mim
                </span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-blue-700 hover:text-blue-900 font-medium">
                    Esqueci minha senha
                </a>
            @endif
        </div>

        {{-- Botão ENTRAR --}}
        <div class="mt-6">
            <button
                type="submit"
                class="w-full py-2.5 rounded-md bg-blue-700 hover:bg-blue-800 text-white font-semibold tracking-wide shadow-md transition-colors duration-150">
                ENTRAR
            </button>
        </div>
    </form>
</x-guest-layout>
