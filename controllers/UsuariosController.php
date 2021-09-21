<?php

class UsuariosController{
        
    var $UsuarioModel;
    var $ConfigSis;

    public function __construct($cfgsis){

        require_once("models/UsuariosModel.php");
        $this -> UsuarioModel = new UsuariosModel($cfgsis['banco_de_dados']);    

        $this -> ConfigSis = $cfgsis;

    }

    public function validaLogin(){
           
        $login = trim( strtolower( $_POST["usuarioNomeLogin"] ) );
        $password = md5(trim($_POST[ "senha" ]));
        $this -> UsuarioModel -> consultaUsuarioLogin( $login );
        $result = $this -> UsuarioModel -> getConsult();

        if( $result != false 
        and $result -> num_rows > 0 
        and $linhausuario = $result -> fetch_assoc() 
        and ($linhausuario[ 'senha' ] == $password or empty($linhausuario['senha'])) ){

            $this -> UsuarioModel -> consultaPerfil($linhausuario['fk_id_perfil_usuario']);
            $result = $this -> UsuarioModel -> getConsult();

            $_SESSION['tipo_cadastro'] = 0;
            if( $result != false and $linhaPerfil = $result -> fetch_assoc() ){
                $_SESSION['tipo_cadastro'] = (int)$linhaPerfil['tipo_cadastro'];
            }
            $_SESSION['cnpj'] = $linhaPerfil['cnpj'];
            $_SESSION['nome_razaosocial'] = $linhaPerfil['nome_razaosocial'];
            $_SESSION['nome_fantasia'] = $linhaPerfil['nome_fantasia'];
            $_SESSION['email'] = $linhaPerfil['email'];
            $_SESSION['telefone'] = $linhaPerfil['telefone'];
            $_SESSION['endereco'] = $linhaPerfil['endereco'];
            $_SESSION['id_perfil'] = $linhausuario['fk_id_perfil_usuario'];
            $_SESSION['id_usuario'] = $linhausuario['id_usuario'];
            $_SESSION['usuarioEmail'] = $linhausuario['email'];
            $_SESSION['usuarioNomeLogin'] = $linhausuario['login'];
            $_SESSION['nivel'] = $linhausuario['nivel'];
            $_SESSION['usuarioSituacao'] = "loged";

            if( ! isset( $_SESSION[ "usuarioErroEsperar" ] ) ){
                $_SESSION['usuarioErroEsperar'] = .1;
            }

            header("Location: index.php?c=m&a=i");
            
        }else{

            $_SESSION['usuarioSituacao'] = "erro";
            sleep($_SESSION['usuarioErroEsperar']);
            header("Location: index.php?c=m&a=l");
            exit;          
        }

    }

    public function trocaDeSenha( $id ){
                
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> UsuarioModel -> consultaUsuarioId( $id );
        $aUsuario = $this -> UsuarioModel -> getConsult() -> fetch_assoc();

        
        require_once("views/usuarios/LoginHeader.php");
        require_once("views/usuarios/TrocarSenha.php");
        
    }

    public function validaTrocaDeSenha(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $arrayUsuarios["id_usuario"]    = $_POST["id_usuario"];
        $arrayUsuarios["senha"]         = md5( $_POST["senhaNovaUsuario"] );
        
        if( $_POST["senha"] != $arrayUsuarios["senha"] ){

            if( ! isset( $_SESSION[ "usuarioErroEsperar" ] ) ){
                $_SESSION['usuarioErroEsperar'] = .1;
            }

            $this -> UsuarioModel -> atualizaSenhaUsuario($arrayUsuarios);
            if( $this -> UsuarioModel -> getConsult() ){

                echo('<script>alert("Senha alterada, com sucesso!");</script>');
                header("Location: ?c=m&a=sd");
            
            }else{
                $_SESSION['usuarioSituacao'] = "erro";
                sleep($_SESSION['usuarioErroEsperar']);
                require_once("views/falha.php");
            }
        }else{
            echo('<script>alert("A nova senha precisa ser diferente da antiga!");</script>');
            $this -> trocaDeSenha( $arrayUsuarios["id_usuario"] );
        }

    }

    public function reiniciaSenha( $id ){
                
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(0);
        
        $this -> UsuarioModel -> consultaUsuarioId( $id );
        $arrayUsuarios = $this -> UsuarioModel -> getConsult() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> reiniciaSenhaAction( $arrayUsuarios );

        }else{
            
            require_once("views/falha.php");

        }

        $this -> listaUsuarios();

    }

    public function reiniciaSenhaAction( $arrayUsuarios ){
        
        $novaSenha = $this -> gerar_senha( 6, true, true, true, true );
        $prazoDias = 1;
        $validadeSenha = new DateTime();
        $prazoSenha = new DateInterval( "P" . $prazoDias . "D" );
        $validadeSenha -> add( $prazoSenha );

        $arrayUsuarios["senha"]         = md5( $novaSenha );
        $arrayUsuarios["senhaValidade"] = $validadeSenha -> format( "Y-m-d" );
        
        $this -> UsuarioModel -> atualizaSenhaUsuario( $arrayUsuarios );
        if( $this -> UsuarioModel -> getConsult() ){
            
            // Para fins de teses, a nova senha aparece na tela 
            echo "<script>alert('A nova senha será: $novaSenha');</script>";

            return $novaSenha;

        }else{

            require_once("views/falha.php");
            exit;

        }

    }

    public function esqueceuSenha(){

        require_once("views/usuarios/LoginHeader.php");
        require_once("views/usuarios/RecuperaSenha.php");

    }

    public function esqueceuSenhaAction(){
        
        $this -> UsuarioModel -> consultaUsuarioEmail( $_POST['email'] );
        $arrayUsuarios = $this -> UsuarioModel -> getConsult() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $novaSenha = $this -> reiniciaSenhaAction( $arrayUsuarios );
            
            // Para fins de teses, a nova senha aparece na tela 
            echo "<script>alert('Sua nova senha é: $novaSenha');</script>";
    
            /* echo "<script>alert('Se tiver informado seu e-mail, corretamente, 
                    e o mesmo estiver cadastrado, dentro de 5 minutos, 
                    você receberá uma mensagem.');</script>"; */
                    
        }

        header("Location: index.php?c=m&a=l");exit;

    }

    public function index(){       

        $this->listaUsuarios();
        
    }

    public function insereUsuario(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(0);
        
        require_once("views/header.php");
        require_once("views/usuarios/InsereUsuario.php");
        require_once("views/footer.php");

    }

    public function insereUsuarioAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        // Confere se login já está em uso
        $this -> UsuarioModel -> consultaUsuarioLogin( $_POST["usuarioLogin"] );
        $arrayUsuarios = $this -> UsuarioModel -> getConsult() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            echo '<script>alert("já existe usuário com este nome de usuário informado");window.history.back();</script>';
            exit;

        }

        // Confere se e-mail já está em uso
        $this -> UsuarioModel -> consultaUsuarioEmail( $_POST["usuarioEmail"] );
        $arrayUsuarios = $this -> UsuarioModel -> getConsult() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            echo '<script>alert("já existe usuário com este e-mail informado");window.history.back();</script>';
            exit;

        }

        $arrayUsuarios["usuarioLogin"] = mb_convert_case( $_POST["usuarioLogin"],  MB_CASE_TITLE, 'UTF-8' );
        $arrayUsuarios["usuarioEmail"] = $_POST["usuarioEmail"];
        $arrayUsuarios["usuarioTelefoneCelular"] = $_POST["usuarioTelefoneCelular"];
        $arrayUsuarios["usuarioNivel"] = $_POST["usuarioNivel"];
        $arrayUsuarios["usuarioSenha"] = md5( $_POST["usuarioSenha"] );
        $arrayUsuarios["perfilId"] = $_POST["perfilId"];
 
        $this -> UsuarioModel -> insereUsuario( $arrayUsuarios );
        
        $idUsuario = $this -> UsuarioModel -> getConsult();
        
        if( $idUsuario > 0 ){
                    
        }else{

            require_once("views/falha.php");
            exit;
            return;

        }

        $this -> listaUsuarios();

    }

    public function atualizaUsuario( $id_usuario ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(0);
        
        $this -> UsuarioModel -> consultaUsuarioId( $id_usuario );
        $result = $this -> UsuarioModel -> getConsult();

        if( $arrayUsuarios = $result -> fetch_assoc() ){
            require_once("views/header.php");
            require_once("views/usuarios/AlteraUsuario.php");
            require_once("views/footer.php");
        }
        
    }

    public function atualizaUsuarioAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $arrayUsuarios["usuarioId"] = $_POST["usuarioId"];
        $arrayUsuarios["usuarioTelefoneCelular"] = $_POST["usuarioTelefoneCelular"];
        $arrayUsuarios["usuarioNivel"] = $_POST["usuarioNivel"];
        $arrayUsuarios["usuarioEmail"] = $_POST["usuarioEmail"];
        $arrayUsuarios["usuarioPerfilId"] = $_POST["usuarioPerfilId"];
        
        $this -> UsuarioModel -> atualizaUsuario($arrayUsuarios);
        if( $this -> UsuarioModel -> getConsult() != false ){
        
        }
        
        $this -> index();

    }

    public function listaUsuarios(){
        
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> UsuarioModel -> listaUsuarios($_SESSION["id_perfil"]);

        $result = $this -> UsuarioModel -> getConsult();
        
        $arrayUsuarios = array();
        while( $linha = $result -> fetch_assoc() ) {
            array_push( $arrayUsuarios, $linha );
        }            
        
        require_once("views/header.php");
        require_once("views/usuarios/ListaUsuarios.php");
        require_once("views/footer.php");
        
    }      
  
    public function removeUsuario( $usuarioId ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(9);
        
        $this -> UsuarioModel -> removeUsuario($usuarioId);
        if( $this -> UsuarioModel -> getConsult() != false ){

        }
        
        $this -> index();

    }

}

?>