<div class="container">

    <?php
    if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
        echo("<h1>Acesso proibido</h1>");
        session_destroy();
    }else{
    ?>

    <h2>Cadastro de Usuário</h2>

    <p>
    No formulário a seguir, preencha os campos solicitados e, para confirmar, clique em Salvar.<br>
    Ao concluir o cadastro, uma mensagem será enviada, por e-mail, contendo uma senha provisória e instruções.
    </p>
    <br>

    <form action="?c=u&a=ia" method="post" enctype="multipart/form-data" name="form1" id="form1" 
    onsubmit="return Validar(this);">

    <div class="form-group">
        <label for="usuarioLogin">Nome de usuário:</label>
        <input type="text" class="form-control" name="usuarioLogin" id="usuarioLogin" 
        placeholder="exemplo: alan_1945" pattern="[_0-9a-zA-Z]{4,50}" title="4 à 50 caracteres, somente aceita-se números, letras e sublinhados."
        required>
    </div>
    <div class="form-group">
        <label for="usuarioSenha">Senha</label>
        <input type="password" name="usuarioSenha" id="usuarioSenha" class="form-control" 
        placeholder="Nova senha" required>
    </div>
    <div class="form-group">
        <label for="senhaRepetidaUsuario">Repita a senha</label>
        <input type="password" name="senhaRepetidaUsuario" id="senhaRepetidaUsuario" class="form-control" 
        placeholder="Repita a senha" required>
    </div>
    <div class="form-group">
        <label for="usuarioNivel" <?php if( !verificaNivelAcesso(9) ){ echo("hidden"); }?> >Nível de acesso:</label>
        <input type="number" min=0 max=9 class="form-control" name="usuarioNivel" id="usuarioNivel" 
        placeholder="exemplo: 3" 
        value=0 <?php if( !verificaNivelAcesso(9) ){ echo("hidden"); }?> >
    </div>
    <div class="form-group">
        <label for="usuarioEmail">Correio eletrônico:</label>
        <input type="email" class="form-control" name="usuarioEmail" id="usuarioEmail" 
                placeholder="exemplo: alan@provedor_de_email.com" required>
    </div>
    <div class="form-group">
        <label for="usuarioTelefoneCelular">Telefone celular(com DDD):</label>
        <input type="tel" class="form-control" name="usuarioTelefoneCelular" id="usuarioTelefoneCelular" 
                placeholder="exemplo: 151993988725" pattern="[0-9]{11}" title="11 dígitos, somente números" required>
    </div>
    
        <!-- Campos ocultos -->
        <input type="number" class="form-control" name="perfilId" id="perfilId" 
        value=<?=$_SESSION['id_perfil']?>
        hidden>
        
        <button type="submit" class="btn btn-success">Salvar</button>

    </form>

    <?php
    }
    ?>

    <script language="javascript">

        function Validar( form1 ){
            
            var regularLogin = new RegExp("^[0-9_a-zA-Z\b]+$");
            var regularTelefone = new RegExp("^[0-9]+$");

            // Verificar nome de usuário:
            if( !regularLogin.test( form1.usuarioNomeLogin.value ) ){
                alert("Nome de usuário não pode ter nada além de letras, números e sublinhados! Nem mesmo espaços em branco!");
                form1.usuarioNomeLogin.focus();
                return false;
            }

            // Verificar telefone:
            if( !regularTelefone.test( form1.perfilTelefoneCelular.value ) ){
                alert("Telefone não pode ter nada além de números! Nem mesmo espaços em branco!");
                form1.perfilTelefoneCelular.focus();
                return false;
            }

        }
        
    </script> 

</div>