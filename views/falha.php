<?php
    global $diretorios;
?>
<!doctype html>
<html lang="pt">
  <head>

    <title>Painel Website de LSP</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Página de aviso de senha expirada em Website de LSP">
    <meta name="author" content=<?= $this -> ConfigSis['autoria'] ?>>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f80b1e2089.js" crossorigin="anonymous"></script>
        
  </head>

    <body class="text-center text-dark" style="background-color: #ffddaa; height: 100%;position: relative;">
    <div class="row-sm">
        <div class="col-sm">
            
            <?php
            if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["fk_id_perfil_usuario"]) ){
                echo("<h1>Acesso proibido</h1>");
            }else{
            ?>

            <h1>Algo deu errado</h1>

            <h5>Desculpe, houve uma falha.</h5>

            <p>
            Experimente retornar à tela anterior e tentar novamente.
            </p>
            <br><br>

            <a class="btn btn-lg btn-primary" href="#" onclick="window.history.back();">
                Voltar
            </a>
<?php
}
?>
            <a href="https://Website de LSP.com.br">
                <p class="mt-5 mb-3 text-info">
                &copy; 2021, Website de LSP<br>
                Website de LSP
                </p>
            </a>    
        </div> 
    </div>
  </body>
</html>