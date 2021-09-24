<?php

class UsuariosController{
        
    var $UsuarioModel;
    var $sisConfig;

    public function __construct($cfgsis){

        require_once("models/UsuariosModel.php");
        $this -> UsuarioModel = new UsuariosModel($cfgsis['banco_de_dados']);    

        $this -> sisConfig = $cfgsis;

    }

    public function ValidaLogin(){
           
        $login = trim( strtolower( $_POST["usuarioNomeLogin"] ) );
        $password = md5(trim($_POST[ "senha" ]));
        $this -> UsuarioModel -> consultaUsuarioLogin( $login );
        $result = $this -> UsuarioModel -> ObtemConsulta();

        if( $result != false 
        and $result -> num_rows > 0 
        and $linhausuario = $result -> fetch_assoc() 
        and ($linhausuario[ 'senha' ] == $password or empty($linhausuario['senha'])) ){

            $this -> UsuarioModel -> ConsultaPerfil($linhausuario['fk_id_perfil_usuario']);
            $result = $this -> UsuarioModel -> ObtemConsulta();

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
            exit;
            
        }else{

            $_SESSION['usuarioSituacao'] = "erro";
            sleep($_SESSION['usuarioErroEsperar']);
            header("Location: index.php?c=m&a=l");
            exit;          
        }

    }

    public function TrocaDeSenha( $id ){
                
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> UsuarioModel -> consultaUsuarioId( $id );
        $aUsuario = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();

        
        require_once("views/usuarios/LoginHeader.php");
        require_once("views/usuarios/TrocarSenha.php");
        
    }

    public function ValidaTrocaDeSenha(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $arrayUsuarios["id_usuario"] = $_POST["id_usuario"];
        $arrayUsuarios["senha"] = md5( $_POST["senhaNovaUsuario"] );
        
        $this -> UsuarioModel -> consultaUsuarioId( $arrayUsuarios["id_usuario"] );
        $aUsuario = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        
        if( $aUsuario['senha'] != $arrayUsuarios["senha"] ){

            if( ! isset( $_SESSION[ "usuarioErroEsperar" ] ) ){
                $_SESSION['usuarioErroEsperar'] = .1;
            }

            $this -> UsuarioModel -> atualizaSenhaUsuario($arrayUsuarios);
            if( $this -> UsuarioModel -> ObtemConsulta() ){

                $this -> ReportaSucesso('sua senha foi alterada, com sucesso!',null,'?c=m&a=sd');
            
            }else{

                $_SESSION['usuarioSituacao'] = "erro";
                sleep($_SESSION['usuarioErroEsperar']);
                $this -> ReportaFalha('houve algum problema na tentativa de alterar senha do usuário!');

            }

        }else{

            $this -> ReportaFalha('a nova senha precisa ser diferente da antiga!', 'Inconsistência');

        }

    }

    public function ReiniciaSenha( $id ){
                
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(0);
        
        $this -> UsuarioModel -> consultaUsuarioId( $id );
        $arrayUsuarios = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReiniciaSenhaAction( $arrayUsuarios );

        }else{
            
            require_once("views/falha.php");

        }

        $this -> ListaUsuarios();

    }

    public function ReiniciaSenhaAction( $arrayUsuarios ){
        
        $novaSenha = $this -> GeraSenha( 8, true, true, true, true );
        $prazoDias = 1;
        $validadeSenha = new DateTime();
        $prazoSenha = new DateInterval( "P" . $prazoDias . "D" );
        $validadeSenha -> add( $prazoSenha );

        $arrayUsuarios["senha"]         = md5( $novaSenha );
        $arrayUsuarios["senhaValidade"] = $validadeSenha -> format( "Y-m-d" );
        
        $this -> UsuarioModel -> atualizaSenhaUsuario( $arrayUsuarios );
        if( $this -> UsuarioModel -> ObtemConsulta() ){
            
            // Para fins de teses, a nova senha aparece na tela 
            // Quando possível, codificar para ser enviada por e-mail
            $this -> ReportaSucesso( "sua nova senha é temporária e será:<br><br>
            <b>$novaSenha</b><br><br>
            Ela tem um prazo de 24h. Depois, ela será desativada.<br>
            Troque-a, o quanto antes.", "Reinicio de Senha", "?c=m&a=sd" );
            /* echo "<script>alert('Se tiver informado seu e-mail, corretamente, 
                    e o mesmo estiver cadastrado, dentro de 5 minutos, 
                    você receberá uma mensagem contendo instruções.');</script>"; */
            return $novaSenha;

        }else{

            $this -> ReportaFalha(null,null);

        }

    }

    public function EsqueceuSenha(){

        require_once("views/usuarios/LoginHeader.php");
        require_once("views/usuarios/RecuperaSenha.php");

    }

    public function EsqueceuSenhaAction(){
        
        $this -> UsuarioModel -> consultaUsuarioEmail( $_POST['email'] );
        $arrayUsuarios = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $novaSenha = $this -> ReiniciaSenhaAction( $arrayUsuarios );
            
        }else{

            $this -> ReportaFalha(null,null);
            
        }

    }

    public function Index(){       

        $this->ListaUsuarios();
        
    }

    public function InsereUsuario(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(0);
        
        require_once("views/header.php");
        require_once("views/usuarios/InsereUsuario.php");
        require_once("views/footer.php");

    }

    public function InsereUsuarioAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        // Confere se login já está em uso
        $this -> UsuarioModel -> consultaUsuarioLogin( $_POST["usuarioLogin"] );
        $arrayUsuarios = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReportaFalha("já existe usuário com o mesmo nome de usuário informado","Inconsistência");
            exit;

        }

        // Confere se e-mail já está em uso
        $this -> UsuarioModel -> consultaUsuarioEmail( $_POST["usuarioEmail"] );
        $arrayUsuarios = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReportaFalha("já existe usuário com o mesmo e-mail informado","Inconsistência");
            exit;

        }

        $arrayUsuarios["usuarioLogin"] = mb_convert_case( $_POST["usuarioLogin"],  MB_CASE_TITLE, 'UTF-8' );
        $arrayUsuarios["usuarioEmail"] = $_POST["usuarioEmail"];
        $arrayUsuarios["usuarioTelefoneCelular"] = $_POST["usuarioTelefoneCelular"];
        $arrayUsuarios["usuarioNivel"] = $_POST["usuarioNivel"];
        $arrayUsuarios["usuarioSenha"] = md5( $_POST["usuarioSenha"] );
        $arrayUsuarios["perfilId"] = $_POST["perfilId"];
 
        $this -> UsuarioModel -> InsereUsuario( $arrayUsuarios );
        
        $idUsuario = $this -> UsuarioModel -> ObtemConsulta();
        
        if( $idUsuario > 0 ){

            $this -> ReportaSucesso( "usuário cadastrado, com sucesso!", null, "?c=u&a=l" );

        }else{

            $this -> ReportaFalha(null,null);

        }

    }

    public function AtualizaUsuario( $id_usuario ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(0);
        
        $this -> UsuarioModel -> consultaUsuarioId( $id_usuario );
        $result = $this -> UsuarioModel -> ObtemConsulta();

        if( $arrayUsuarios = $result -> fetch_assoc() ){
            require_once("views/header.php");
            require_once("views/usuarios/AlteraUsuario.php");
            require_once("views/footer.php");
        }
        
    }

    public function AtualizaUsuarioAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $arrayUsuarios["usuarioId"] = $_POST["usuarioId"];
        $arrayUsuarios["usuarioTelefoneCelular"] = $_POST["usuarioTelefoneCelular"];
        $arrayUsuarios["usuarioNivel"] = $_POST["usuarioNivel"];
        $arrayUsuarios["usuarioEmail"] = $_POST["usuarioEmail"];
        $arrayUsuarios["usuarioPerfilId"] = $_POST["usuarioPerfilId"];
        
        $this -> UsuarioModel -> AtualizaUsuario($arrayUsuarios);
        if( $this -> UsuarioModel -> ObtemConsulta() != false ){
            $this -> ReportaSucesso('seu usuário de acesso foi atualizado, com sucesso!',null,'?c=u&a=l');
        }else{
            $this -> ReportaFalha('houve algum problema na tentativa de atualizar seu usuário de acesso!');
        }        

    }

    public function ListaUsuarios(){
        
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> UsuarioModel -> ListaUsuarios($_SESSION["id_perfil"]);

        $result = $this -> UsuarioModel -> ObtemConsulta();
        
        $arrayUsuarios = array();
        while( $linha = $result -> fetch_assoc() ) {
            array_push( $arrayUsuarios, $linha );
        }            
        
        require_once("views/header.php");
        require_once("views/usuarios/ListaUsuarios.php");
        require_once("views/footer.php");
        
    }      
  
    public function RemoveUsuario( $usuarioId ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso(9);
        
        $this -> UsuarioModel -> RemoveUsuario($usuarioId);
        if( $this -> UsuarioModel -> ObtemConsulta() != false ){
            $this -> ReportaSucesso("usuário removido, com sucesso!",null,"?c=u&a=l");
        }else{
            $this -> ReportaFalha(null,null);
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

    // Exemplo retirado do website DevMedia
    function GeraSenha( $tamanho, $maiusculas, $minusculas, $numeros, $simbolos ){

        $senha = '';
        $ma = "ABCDEFGHJKLMNPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = mb_strtolower($ma); // $mi contem as letras minusculas
        $nu = "123456789"; // $nu contem os números
        $si = "#$%&*"; // $si contem os símbolos
       
        if ($maiusculas){
              // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
              $senha .= str_shuffle($ma);
        }
       
        if ($minusculas){
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($mi);
        }
    
        if ($numeros){
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($nu);
        }
    
        if ($simbolos){
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($si);
        }
    
        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return mb_substr( str_shuffle( $senha ), 0, $tamanho );

      }

}

?>