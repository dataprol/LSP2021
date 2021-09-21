        <link href="assets/css/signin.css" rel="stylesheet">

        <form class="form-signin" action="?c=u&a=vl" method=POST name="formulario" id="formulario">
            
            <h2 class="h4 mb-3 font-weight-normal text-dark">
                <b>Trabalho de LSP</b>
            </h2>
    
            <h1 class="h3 mb-3 font-weight-normal text-dark">
                <b>Acesso ao Sistema</b>
            </h1>

            <?php
            if( ! isset( $_SESSION[ "usuarioErroEsperar" ] ) ){
                $_SESSION['usuarioErroEsperar'] = .1;
            }
            if( ! isset( $_SESSION[ "usuarioSituacao" ] ) ){
                $_SESSION['usuarioSituacao'] = "logoff";
            }
            if( $_SESSION['usuarioSituacao'] == "erro" ){
                echo( '<p class="mb-3 text-danger">Usuário ou senha errado!</p>');
                $_SESSION['usuarioSituacao'] ="";
                $_SESSION['usuarioErroEsperar'] += $_SESSION['usuarioErroEsperar'];
                if( $_SESSION['usuarioErroEsperar'] >= 5 ){
                    echo '<p>Teve muitas tentativa erradas. Aguarde alguns minutos, antes de tentar novamente.</p>';
                }
                }
            ?>
            <label for="usuarioNomeLogin" class="sr-only">Usuário</label>
            <input type="login" name="usuarioNomeLogin" id="usuarioNomeLogin" class="form-control" placeholder="Seu nome de usuário" required autofocus>
            
            <label for="senha" class="sr-only">Senha</label>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Sua senha" required>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
            
            <br>
            <p class="font-weight-bold">
            <a href="?c=u&a=fp">Esqueceu sua senha? Clique aqui.</a>
            </p>
            
            <p class="font-weight">
            <a href="?c=c&a=i">Não tem cadastro? Clique aqui.</a>
            </p>

            <a href="https://Website de LSP.com.br">
                <p class="mt-5 mb-3 text-info">
                &copy; 2021, Website de LSP
                </p>
            </a>
        </form>

    </body>
</html>
