    <link href="assets/css/change_password.css" rel="stylesheet">

    <?php
    if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["fk_id_perfil_usuario"]) ){
        echo("<h1>Acesso proibido</h1>");
    }else{
    ?>

    <form class="form-signin" action="?c=u&a=vcp" method="POST" name="formulario" id="formulario" 
    onsubmit="return Validar(this);" >
                
        <h1 class="h3 mb-3 font-weight-normal text-dark">
            <b><center>Troca de Senha</center></b>
        </h1>

        <label for="senhaNovaUsuario" class="sr-only">Nova senha</label>
        <input type="password" name="senhaNovaUsuario" id="senhaNovaUsuario" class="form-control" 
        placeholder="Nova senha" required autofocus>

        <label for="senhaRepetidaUsuario" class="sr-only">Repita a senha</label>
        <input type="password" name="senhaRepetidaUsuario" id="senhaRepetidaUsuario" class="form-control" 
        placeholder="Repita a senha" required>

        <input type="number" name="id_usuario" id="id_usuario" class="form-control" 
        value=<?=$aUsuario["id_usuario"]?> hidden>        

        <button class="btn btn-lg btn-success btn-block" type="submit">Confirmar</button>
        <a class="btn btn-lg btn-danger btn-block" href="#" onclick="window.history.back();">
            Cancelar
        </a>

        <a href="https://Website de LSP.com.br">
            <p class="mt-5 mb-3 text-info">
            &copy; 2021, Website de LSP <br>Website de LSP
            </p>
        </a>

    </form>

    <script language="javascript">
        function Validar( form1 ){

            // Comparar senhas:
            if( form1.senhaRepetidaUsuario.value != form1.senhaNovaUsuario.value ){
                alert("As senhas digitadas estão diferentes!");
                form1.senhaRepetidaUsuario.focus();
                return false;
            }

            if( form1.senhaNovaUsuario.value.length < 8 || form1.senhaRepetidaUsuario.value.length < 8 ){
                alert("A senha requer o mínimo de 8 caracteres!");
                form1.senhaNovaUsuario.focus();
                return false;
            }

        }
    </script> 
<?php
}
?>
  </body>
</html>
