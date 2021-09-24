<?php

class PerfisController{
    
    var $PerfilModel;
    var $sisConfig;
    
    public function __construct($cfgsis){
        
        if( !isset( $_SESSION[ "usuarioNomeLogin" ] ) 
        && $_SERVER['QUERY_STRING'] != 'c=c&a=i'
        && $_SERVER['QUERY_STRING'] != 'c=c&a=ia' ){
            header("Location: index.php?c=m&a=l");
            exit;
        }
        require_once("models/PerfisModel.php");
        $this -> PerfilModel = new PerfisModel($cfgsis['banco_de_dados']);
        
        $this -> sisConfig = $cfgsis;

    }

    public function Index(){

        require_once("views/header.php");
        require_once("views/home.php");
        require_once("views/footer.php");

    }

    public function InserePerfil(){

        require_once("views/header.php");
        require_once("views/Perfis/inserePerfil.php");
        require_once("views/footer.php");
        
    }

    public function InserePerfilAction(){
        
        // Pega o índice do campo de tipo de cadastro
        $_POST["perfilTipo"] = array_search( $_POST["perfilTipo"], $this -> sisConfig['tipos_de_cadastro'] ); 
        
        // Grava o novo pefil
        $this -> PerfilModel -> InserePerfil( $_POST );
        
        // Pega o Id do novo perfil, para armazenar na tabela de usuário
        $result = $this -> PerfilModel -> ObtemConsulta();
        if( $result != false ){
            
            // Sucesso!
            $arrayUsuarios['perfilId'] = $result;
            $arrayUsuarios['usuarioNivel'] = 99;
            $arrayUsuarios['usuarioLogin'] = $_POST['usuarioLogin'];
            $arrayUsuarios['usuarioSenha'] = md5( $_POST['usuarioSenha'] );
            $arrayUsuarios['usuarioEmail'] = $_POST['perfilEmail'];
            $arrayUsuarios['usuarioTelefoneCelular'] = $_POST['perfilTelefone'];
    
            // Cadastra o usuário
            $this -> PerfilModel -> InsereUsuario($arrayUsuarios);
            $result = $this -> PerfilModel -> ObtemConsulta();
            if( $result != false ){

                // Sucesso!
                $usuarioId = $result;
                $this -> ReportaSucesso('seu cadastro foi concluído com sucesso!','?c=m&a=i');

            }else{
                $this -> ReportaFalha('houve uma falha na tentativa de gravação do usuário para seu perfil!');
            }

        }else{
            $this -> ReportaFalha('houve uma falha na tentativa de gravação de seu perfil!');
        }


    }

    public function AtualizaPerfil( $id_perfil ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PerfilModel -> ConsultaPerfil( $id_perfil );
        $result = $this -> PerfilModel -> ObtemConsulta();

        if( $arrayPerfil = $result -> fetch_assoc() ){
            require_once("views/header.php");
            require_once("views/perfis/AlteraPerfil.php");
            require_once("views/footer.php");
        }else{
            $this -> ReportaFalha(null,null);
        }

    }

    public function AtualizaPerfilAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        // Pega o índice do campo de tipo de cadastro
        $_POST["perfilTipo"] = array_search( $_POST["perfilTipo"], $this -> sisConfig['tipos_de_cadastro'] ); 

        $this -> PerfilModel -> AtualizaPerfil($_POST);
        if( $this -> PerfilModel -> ObtemConsulta() != false ){            
            $this -> ReportaSucesso("perfil atualizado, com sucesso!",null,"?c=m&a=i");
        }else{
            $this -> ReportaFalha('houve uma falha na tentativa de atualização de seu perfil!');
        }        
        
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

}
?>