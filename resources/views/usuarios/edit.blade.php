<h1>Editar Usuário: {{ $usuario->nome }}</h1>

<form action="/usuarios/{{ $usuario->id }}" method="POST">
    @csrf
    @method('PUT') @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
            <strong>Ops! Algo deu errado:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="{{ $usuario->nome }}">
    </div>

    <div>
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" value="{{ $usuario->login }}">
    </div>

    <hr>
    <p>Preencha os campos abaixo APENAS se quiser alterar a senha:</p>
    <div>
        <label for="password">Nova Senha (mínimo 8 caracteres):</label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <label for="password_confirmation">Confirme a Nova Senha:</label>
        <input type="password" id="password_confirmation" name="password_confirmation">
    </div>
    <hr>

    <div>
        <label for="nivel_acesso">Nível de Acesso:</label>
        <select id="nivel_acesso" name="nivel_acesso">
            <option value="caixa" @if($usuario->nivel_acesso == 'caixa') selected @endif>
                Caixa
            </option>
            <option value="admin" @if($usuario->nivel_acesso == 'admin') selected @endif>
                Admin
            </option>
        </select>
    </div>

    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="/usuarios">Voltar para a lista</a>