<!doctype html>
<html lang="pt">
    <head>
    
        <title>
            <?php if($this -> sisConfig['modo_de_trabalho']=='dev'){ ?>DEV<?php } ?>Website de LSP
        </title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, shrink-to-fit=no">
        <meta name="description" content="Website de LSP">
        <meta name="author" content=<?= $this -> sisConfig['autoria'] ?>>
		<meta name="robots" content="index,nofollow,noimageindex">
		<meta name="robots" content="noarchive">
		<meta name="googlebot" content="index,nofollow,noimageindex">
		<meta name="googlebot" content="noarchive">
		<meta http-equiv="Expires" content="0">
		<META http-equiv="Pragma" content="no-cache">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<script src="https://kit.fontawesome.com/f80b1e2089.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/css/estilo_proprio.css">

        <!--[if lt IE 9]>
        <script src="assets/js/html5.js"></script>
        <![endif]-->

    </head>

    <body>
        <header>
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
        
            <div class="text-white m-2 p-0">
                <h4>
                <?php if($this -> sisConfig['modo_de_trabalho']=='dev'){?>
                    <font color=#aa0000>
                        <b>
                            DEV
                        </b>
                    </font>
                <?php } ?>
                Website de LSP
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
    
                    <div class="navbar-nav">
    
                        <div class="nav-item">
                            <a class="nav-link active" href="?c=m&a=i" role="button" aria-haspopup="true" aria-expanded="false">
                                Início
                            </a>
                        </div>
                        <?php if( verificaNivelAcesso(5) ){ ?>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUsuarios" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Usuários
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownUsuarios">
                                    <a class="dropdown-item" href="?c=u&a=l">Listar</a>
                                    <a class="dropdown-item" href="?c=u&a=i">Adicionar</a>
                                </div>
                            </div>                            
                        <?php } ?>
                        
                    </div>
                </div>
                <div class="navbar-nav">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle bg-primary" href="#" id="navbarDropdownPerfil" 
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
                            <a class="dropdown-item" href="?c=u&a=u&id=<?=$_SESSION['id_usuario']?>">Atualizar cadastro</a>
                            <a class="dropdown-item" href=".">Perfil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="?c=m&a=sd">Sair</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </nav>
        </header>
        <aside>
        </aside>
        <section class="mt-5 pt-5 pb-5">