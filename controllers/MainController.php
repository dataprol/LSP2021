<?php

    class MainController{

        var $MainModel;
        var $sisConfig;
    
        public function __construct( $sisConfig ){
        
            require_once("models/MainModel.php");
            $this -> MainModel = new MainModel( $sisConfig['banco_de_dados'] );
            
            $this -> sisConfig = $sisConfig;

        }
    
        public function Index(){

            if( !isset($_SESSION["usuarioNomeLogin"]) ){
                header("Location: index.php?c=m&a=l");exit;
            }

            if( $_SESSION['id_perfil'] > 0 ){
                $this -> MainModel -> ConsultaPerfil( $_SESSION['id_perfil'] );
                $result = $this -> MainModel -> ObtemConsulta();
                if( $result != false ){
                    $cadastroPerfil = $result -> fetch_assoc();
                    require_once("views/header.php");
                    require_once("views/home.php");
                    require_once("views/footer.php");        
                }else{
                    $this -> ReportaFalha('houve algum problema na consulta do perfil do usuário!');
                }
            }else{
                $this -> ReportaFalha(null,null);
            }

        }

        public function Login(){

            require_once("views/usuarios/LoginHeader.php");
            require_once("views/usuarios/Login.php");

        }

        public function AcessoNegado(){
            
            $this -> ReportaFalha('acesso restrito a usuários específicos.','Acesso negado');

        }

        public function ReportaFalha( $cMensagemDeErro, $cTituloDoErro ){

            require_once("views/header.php");
            require_once("views/falha.php");
            require_once("views/footer.php");        
    
        }
    
        public function ReportaSucesso( $cMensagemDeSucesso, $cTituloDoSucesso, $cCaminhoDoBotaoSucesso ){
        
            require_once("views/header.php");
            require_once("views/sucesso.php");
            require_once("views/footer.php");        
    
        }
                    
        public function DestroySession(){

            $_SESSION = array();
            session_destroy();
            header("Location: index.php?c=m&a=l");exit;

        }

    }
    
?>