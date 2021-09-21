<?php
    global $diretorios;
?>

<div class="container">

    <h2>Atualização de Usuário</h2>

    <p>No formulário a seguir, preenche os campos que você deseja alterar e, para confirmar, clique em Salvar.</p>

    <form action="?c=u&a=ua" method=POST enctype='multipart/form-data' name="form1" id="form1"
        onsubmit="return Validar(this);">

        <div class="form-group">
            <label for="usuarioLogin">Nome de usuário:</label>
            <input type="text" class="form-control" name="usuarioLogin" id="usuarioLogin"
                    value="<?=$arrayUsuarios['login']?>" disabled >
        </div>
        
        <?php if( verificaNivelAcesso(9) ){ ?>        
        <div class="form-group">
            <label for="usuarioNivel">Nível de acesso:</label>
            <input type="number" min=0 max=9 class="form-control" name="usuarioNivel" id="usuarioNivel" 
                    placeholder="exemplo: 3"
                    value="<?=$arrayUsuarios['nivel']?>">
        </div>
        <?php } ?>
        <div class="form-group">
            <label for="usuarioEmail">Correio eletrônico:</label>
            <input type="email" class="form-control" name="usuarioEmail" 
                    value="<?=$arrayUsuarios['email']?>">
        </div>
        <div class="form-group">
            <label for="usuarioTelefoneCelular">Telefone celular:</label>
            <input type="tel" class="form-control" name="usuarioTelefoneCelular" id="usuarioTelefoneCelular" 
                    placeholder="exemplo: 15993988725" pattern="[0-9]{10,11}" title="Até 11 dígitos, com DDD, sendo somente números." required 
                    value=<?=$arrayUsuarios['telefoneCelular']?> >
        </div>
        
        <!-- Campos ocultos -->
        <input type="number" class="form-control" name="usuarioId" 
        value="<?=$arrayUsuarios['id_usuario']?>" 
        hidden >
        <input type="number" class="form-control" name="usuarioPerfilId" id="usuarioPerfilId" 
        placeholder="exemplo: 1234"
        value="<?=$arrayUsuarios['fk_id_perfil_usuario']?>"
        hidden>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>

    </form>

    <script language="javascript">

        function Validar( form1 ){
            
            var regularTelefone = new RegExp("^[0-9]+$");

            // Verificar telefone:
            if( !regularTelefone.test( form1.perfilTelefoneCelular.value ) ){
                alert("Telefone não pode ter nada além de números! Nem mesmo espaços em branco!");
                form1.perfilTelefoneCelular.focus();
                return false;
            }

        }
        
    </script> 

</div>