<?php

class PerfisController{
    
    var $PerfilModel;
    var $ConfigSis;
    
    public function __construct($cfgsis){
        
        if( !isset( $_SESSION[ "usuarioNomeLogin" ] ) 
        && $_SERVER['QUERY_STRING'] != 'c=c&a=i'
        && $_SERVER['QUERY_STRING'] != 'c=c&a=ia' ){
            header("Location: index.php?c=m&a=l");
            exit;
        }
        require_once("models/PerfisModel.php");
        $this -> PerfilModel = new PerfisModel($cfgsis['banco_de_dados']);
        
        $this -> ConfigSis = $cfgsis;

    }

    public function index(){
        require_once("views/header.php");
        require_once("views/footer.php");
    }

    public function inserePerfil(){

        require_once("views/header.php");
        require_once("views/Perfis/inserePerfil.php");
        require_once("views/footer.php");
        
    }

    public function inserePerfilAction(){
        
        // Pega o índice do campo de tipo de cadastro
        $_POST["perfilTipo"] = array_search( $_POST["perfilTipo"], $this -> ConfigSis['tipos_de_cadastro'] ); 
        
        // Grava o novo pefil
        $this -> PerfilModel -> inserePerfil($_POST);
        
        // Pega o Id do novo perfil, para armazenar na tabela de usuário
        $result = $this -> PerfilModel -> getConsult();
        if( $result != false ){
            
            $arrayUsuarios['perfilId'] = $result;
            $arrayUsuarios['usuarioNivel'] = 99;
            $arrayUsuarios['usuarioLogin'] = $_POST['usuarioLogin'];
            $arrayUsuarios['usuarioSenha'] = $_POST['usuarioSenha'];
            $arrayUsuarios['usuarioEmail'] = $_POST['perfilEmail'];
            $arrayUsuarios['usuarioTelefoneCelular'] = $_POST['perfilTelefone'];
    
            // Cadastra o usuário
            $this -> PerfilModel -> insereUsuario($arrayUsuarios);
            $result = $this -> PerfilModel -> getConsult();
            if( $result != false ){

                $usuarioId = $result;
                header("Location: index.php?c=m&a=i");

            }else{
                echo("<script>alert('Falha na gravação do usuário do novo perfil!');</script>");
            }

        }else{
            echo("<script>alert('Falha na gravação do novo perfil!');</script>");
        }

    }

    public function atualizaPerfil( $id_perfil ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PerfilModel -> consultaPerfil( $id_perfil );
        $result = $this -> PerfilModel -> getConsult();

        if( $arrayPerfil = $result -> fetch_assoc() ){
            require_once("views/header.php");
            require_once("views/perfis/AlteraPerfil.php");
            require_once("views/footer.php");
        }

    }

    public function atualizaPerfilAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        // Pega o índice do campo de tipo de cadastro
        $_POST["perfilTipo"] = array_search( $_POST["perfilTipo"], $this -> ConfigSis['tipos_de_cadastro'] ); 

        $this -> PerfilModel -> atualizaPerfil($_POST);
        if( $this -> PerfilModel -> getConsult() != false ){
        
        }else{
            echo("<script>alert('Falha na atualização de seu perfil!');</script>");
        }
        
        header("Location: index.php?c=m&a=i");
        
    }

}
?>