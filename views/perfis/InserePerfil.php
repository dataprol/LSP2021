<div class="container">

    <h2>Cadastro do Seu Perfil</h2>
    <p>
    No formulário a seguir, preencha os campos solicitados e, para confirmar, clique em Salvar.<br>
    </p>

    <form action="?c=c&a=ia" method="POST" enctype="multipart/form-data" name="form1" id="form1" 
    onsubmit="return Validar(this);">

        <div class="form-group">
            <label for="perfilNome">Título ou Nome Fantasia:</label>
            <input type="text" class="form-control" name="perfilNome" 
            placeholder="Restaurante Comida Na Mesa"
            autofocus>
            <label for="perfilRazaoSocial">Razão Social:</label>
            <input type="text" class="form-control" name="perfilRazaoSocial"
            placeholder="Empresa Nacional de Alimentos SA" required>
            <label for="perfilCNPJ">CNPJ:</label>
            <input type="num" class="form-control" name="perfilCNPJ" 
            placeholder="00.000.000/0000-00" pattern="[0-9]{14}" title="14 dígitos, somente números" 
            required>
            <label for="perfilTipo">Tipo do perfil:</label>
            <select class="form-control" name="perfilTipo" id="perfilTipo" required>
            <option value=""></option>
            <?php
                foreach ( _TIPOS_DE_CADASTRO as $tipo ) {
                    echo "<option value=$tipo>$tipo</option>";
                }
            ?>
            </select>
        </div>
        <hr>
        <div class="form-group">
            <label for="perfilEmail">Correio eletrônico:</label>
            <input type="email" class="form-control" name="perfilEmail" 
            placeholder="seunome@seuprovedor.com" required>
            <label for="tel">Telefone:</label>
            <input type="perfilTelefone" class="form-control" name="perfilTelefone" alt="telefone"
            placeholder="exemplo: 151993988725" pattern="[0-9]{11}" title="11 dígitos, somente números" required>
            <label for="perfilEndereco">Endereço:</label>
            <input type="text" class="form-control" name="perfilEndereco" 
            placeholder="Setor Palácio Presidencial, Zona Cívico-Administrativa, Brasília, DF. CEP 70150-903."
            required>
        </div>
        <!-- Dados para login -->
        <hr>
        <div class="form-group">
            <label for="usuarioLogin">Nome de usuário:</label>
            <input type="text" class="form-control" name="usuarioLogin" id="usuarioLogin" 
            placeholder="exemplo: alan_1945" pattern="[a-zA-Z_0-9]{6,50}" title="É permitido de 6 à 50 caracteres alfabéticos, numéricos e o sublinhado. É proibido espaços entre os caracteres."
            required>
<!--             <label for="usuarioSenha">Senha</label>
            <input type="password" name="usuarioSenha" id="usuarioSenha" class="form-control" 
            placeholder="Nova senha" required>
            <label for="senhaRepetidaUsuario">Repita a senha</label>
            <input type="password" name="senhaRepetidaUsuario" id="senhaRepetidaUsuario" class="form-control" 
            placeholder="Repita a senha" required>
 -->        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        &nbsp;&nbsp;
        <a class="btn btn-danger" href="#" onclick="window.history.back();">
            Voltar
        </a>
    </form>

</div>

<script language="javascript">
    function Validar( form1 ){

        // Comparar senhas:
        if( form1.senhaRepetidaUsuario.value != form1.perfilUsuarioSenha.value ){
            alert("As senhas digitadas estão diferentes!");
            form1.senhaRepetidaUsuario.focus();
            return false;
        }

        if( form1.perfilUsuarioSenha.value.length < 8 || form1.senhaRepetidaUsuario.value.length < 8 ){
            alert("A senha requer o mínimo de 8 caracteres!");
            form1.perfilUsuarioSenha.focus();
            return false;
        }

   }
</script> 
