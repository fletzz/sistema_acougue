<h1>Cadastrar Novo Usuário</h1>

<form action="/usuarios" method="POST">
    @csrf

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

    <div>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
    </div>

    <div>
        <label for="login">Login:</label>
        <input type="text" id="login" name="login">
    </div>

    <div>
        <label for="password">Senha (mínimo 8 caracteres):</label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <label for="password_confirmation">Confirme a Senha:</label>
        <input type="password" id="password_confirmation" name="password_confirmation">
    </div>

    <div>
        <label for="nivel_acesso">Nível de Acesso:</label>
        <select id="nivel_acesso" name="nivel_acesso">
            <option value="caixa">Caixa</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <button type="submit">Salvar Usuário</button>
</form>

<br>
<a href="/usuarios">Voltar para a lista</a>