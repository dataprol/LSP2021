<?php

    class MainController{

        var $ConfigSis;

        public function __construct( $ConfigSis ){
        
            require_once("models/MainModel.php");
            $this -> MainModel = new MainModel( $ConfigSis['banco_de_dados'] );
            
            $this -> ConfigSis = $ConfigSis;

        }
    
        public function index(){

            if( !isset($_SESSION["usuarioNomeLogin"]) ){
                header("Location: index.php?c=m&a=l");exit;
            }

            if( $_SESSION['id_perfil'] > 0 ){
                $this -> MainModel -> consultaPerfil( $_SESSION['id_perfil'] );
                $result = $this -> MainModel -> getConsult();
                if( $result != false ){
                    $cadastroPerfil = $result -> fetch_assoc();
                }else{
                    echo("<script>alert('Houve algum problema na consulta do perfil do usuário!');</script>");
                    exit;
                }
            }

            require_once("views/header.php");
            require_once("views/home.php");
            require_once("views/footer.php");        
    
        }

        public function login(){
            require_once("views/usuarios/LoginHeader.php");
            require_once("views/usuarios/Login.php");
        }

        public function acessoNegado(){

            require_once("views/header.php");
        ?>
            
            <center>
            <div class="row-auto m-2 p-0 ">
                <div class="col-sd-auto m-5 p-4 border shadow" style="background-color: #aa0000;">

                    <h3 class="text-warning">
                        <b>Acesso negado</b>
                    </h3>
                    <hr>

                    <br><br>
                    <p class="text-white">
                        Acesso restrito a usuários específicos.
                    </p>
                    <br><br>

                </div>
            </div>
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">
                Voltar
            </a>
            </center>
            
        <?php
            require_once("views/footer.php");
            exit;
        }
        
        public function destroySession(){
            $_SESSION = array();
            session_destroy();
            header("Location: index.php?c=m&a=l");exit;
        }

    }
    
?>