<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
        
        <div class="text-white m-2 p-0">
            <h4>
            <?php if(_MODO_DE_TRABALHO=='dev'){?>
                <font color=#aa0000>
                    <b>
                        DEV
                    </b>
                </font>
            <?php } ?>
            <?=_PROJETO_TITULO?>
            </h4>
        </div>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarPrincipal" 
        aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        if(isset( $_SESSION[ "usuarioNomeLogin" ] )){
        ?>
            <div class="collapse navbar-collapse" id="navbarPrincipal">

                <div class="navbar-nav mr-auto">

                    <div class="nav-item">
                        <a class="nav-link active" href="?c=m&a=i" role="button" aria-haspopup="true" aria-expanded="false">
                            Início
                        </a>
                    </div>
                    <?php if( verificaNivelAcesso("Operacional") ){ ?>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUsuarios" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Usuários
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownUsuarios">
                                <a class="dropdown-item" href="?c=u&a=l">Listar</a>
                                <?php if( verificaNivelAcesso("Gerencial") ){ ?>
                                    <a class="dropdown-item" href="?c=u&a=i">Adicionar</a>
                                <?php } ?>
                            </div>
                        </div>                            
                    <?php 
                    }
                    if( $_SESSION['tipo_cadastro'] == _TIPO_COMERCIO ){ ?>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUsuarios" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pedidos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownUsuarios">
                                <a class="dropdown-item" href="?c=p&a=l">Listar</a>
                                <a class="dropdown-item" href="?c=p&a=i">Adicionar</a>
                            </div>
                        </div>                            
                    <?php } ?>
                    
                </div>
                <div class="navbar-nav justify-content-end">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPerfil" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
    
                            echo $_SESSION[ "usuarioNomeLogin" ];
                            echo '&nbsp;';
                            
                            $imageFile =  $this -> sisConfig['arquivos'] . "/usuarios/" . trim( $_SESSION['id_usuario'] ) . ".jpg";
                            
                            echo('<img class="rounded-circle" style="max-height: 45px;" src="');
    
                            if( is_file( $imageFile ) ){
    
                                echo( $imageFile );
    
                            }else{
    
                                $imageFile =  $this -> sisConfig['arquivos'] . "/usuarios/";
                                echo( $imageFile . 'usuarioPadrao.jpg' );                            
    
                            }
    
                            echo('">');
    
                            ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPerfil">
                            <a class="dropdown-item" href="?c=u&a=cp&id=<?=$_SESSION['id_usuario']?>">Trocar senha</a>
                            <a class="dropdown-item" href="?c=u&a=u&id=<?=$_SESSION['id_usuario']?>">Atualizar usuário</a>
                            <a class="dropdown-item" href="?c=c&a=u&id=<?=$_SESSION['id_perfil']?>">Atualizar perfil</a>
                            <a class="dropdown-item" href="?c=c&a=d&id=<?=$_SESSION['id_perfil']?>">Excluir perfil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="?c=m&a=sd">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </nav>