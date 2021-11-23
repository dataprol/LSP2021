<?php

class UsuariosController{
        
    var $UsuarioModel;
    var $sisConfig;
    var $mail;

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

        negarAcesso("Operacional");
        
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
        
            // Mensagem por e-mail:
            require_once("email.php");
            
            // Prepara mensagem
            $cEMailDestino = [$arrayUsuarios["email"], $arrayUsuarios["login"]];
            $cEMailAssunto = "Recuperação de Senha para " . $arrayUsuarios["login"];
            $cEMailTituloMensagem = "Recuperação de Senha";
            $cTextoNovaSenha = '
                <p>
                    Usuário: <b>' . $arrayUsuarios["login"] . '</b><br>
                    Senha provisória: <b>' . $novaSenha . '</b><br>
                    <b>Validade: ' . $prazoDias * 24 . ' horas</b>
                </p>';
            $cEMailMensagem = '
                <h1>' . $cEMailTituloMensagem . '</h1>
                <h3>Uma nova senha temporária foi criada, para você recuperar seu acesso!</h3>
                ' . $cTextoNovaSenha . '
                <br>
                <p>
                    Para trocar a senha, acesse o website <b><a href="https://lsp.provisorio.ws">lsp.provisorio.ws</a></b>, 
                    entre com sua senha provisória, clique na sua imagem, no canto superior direito da tela, e clique em <b>Trocar Senha</b>.
                    <br>
                    <b>Troque sua senha, o quanto antes, para evitar seu bloqueio.</b>
                </p>
                <br><br>
                Mensageiro autônomo<br>
                ' . _PROJETO_AUTORIA . '<br>
                ' . _PROJETO_TITULO . '<br>
                ' . _PROJETO_WEBSITELINK ;

            // Envia
            if( !EmailEnviar( $cEMailDestino, $cEMailAssunto, $cEMailTituloMensagem, $cEMailMensagem, $this -> sisConfig["email_servidor"] ) ){
                
                $this -> ReportaFalha(null,'Mensagem não enviada');
                
            }else{
                
                $this -> ReportaSucesso( "se tiver informado seu e-mail, corretamente, 
                    e o mesmo estiver cadastrado, dentro de 5 minutos, 
                    você receberá uma mensagem contendo instruções.", "Recuperação de Senha", "?c=m&a=sd" );

            }
                    
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

        negarAcesso("Operacional");
        
        require_once("views/header.php");
        require_once("views/usuarios/InsereUsuario.php");
        require_once("views/footer.php");

    }

    public function InsereUsuarioAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $novaSenha = $this -> GeraSenha( 8, true, true, true, true );
        $prazoDias = 1;
        $validadeSenha = new DateTime();
        $prazoSenha = new DateInterval( "P" . $prazoDias . "D" );
        $validadeSenha -> add( $prazoSenha );

        // Confere se login já está em uso
        $this -> UsuarioModel -> consultaUsuarioLogin( mb_convert_case( $_POST["usuarioLogin"],  MB_CASE_TITLE, 'UTF-8' ) );
        $arrayUsuarios = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReportaFalha("já existe usuário com o mesmo nome de usuário informado","Inconsistência");
            exit;

        }

        // Confere se e-mail já está em uso
        $this -> UsuarioModel -> consultaUsuarioEmail( strtolower($_POST["usuarioEmail"]) );
        $arrayUsuarios = $this -> UsuarioModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReportaFalha("já existe usuário com o mesmo e-mail informado","Inconsistência");
            exit;

        }

        $arrayUsuarios["usuarioLogin"] = mb_convert_case( $_POST["usuarioLogin"],  MB_CASE_TITLE, 'UTF-8' );
        $arrayUsuarios["usuarioEmail"] = strtolower($_POST["usuarioEmail"]);
        $arrayUsuarios["usuarioTelefoneCelular"] = $_POST["usuarioTelefoneCelular"];
        $arrayUsuarios["usuarioNivel"] = $_POST["usuarioNivel"];
        $arrayUsuarios["usuarioSenha"] = md5( $novaSenha );
        $arrayUsuarios["perfilId"] = $_POST["perfilId"];
 
        $this -> UsuarioModel -> InsereUsuario( $arrayUsuarios );
        
        $idUsuario = $this -> UsuarioModel -> ObtemConsulta();
        
        if( $idUsuario > 0 ){

            // Mensagem por e-mail:
            require_once("email.php");
            
            // Prepara mensagem
            $cEMailDestino = [$arrayUsuarios["usuarioEmail"], $arrayUsuarios["usuarioLogin"]];
            $cEMailAssunto = "Cadastro do novo usuário " . $arrayUsuarios["usuarioLogin"];
            $cEMailTituloMensagem = "Cadastro de Novo Usuário";
            $cTextoNovaSenha = '
                <p>
                    Usuário: <b>' . $arrayUsuarios["usuarioLogin"] . '</b><br>
                    Senha provisória: <b>' . $novaSenha . '</b><br>
                    <b>Validade: ' . $prazoDias * 24 . ' horas</b>
                </p>';
            $cEMailMensagem = '
                <h1>' . $cEMailTituloMensagem . '</h1>
                <h3>Seu usuário de acesso foi criado, com sucesso!</h3>
                ' . $cTextoNovaSenha . '
                <br>
                <p>
                    <b>Troque sua senha, o quanto antes, para evitar seu bloqueio.</b>
                    <br>
                    Para trocar a senha, acesse o website <b>' . _PROJETO_WEBSITELINK . '</b>, 
                    entre com sua senha provisória, clique na sua imagem, no canto superior direito da tela, e clique em <b>Trocar Senha</b>.
                </p>
                <br><br>
                Mensageiro autônomo<br>
                ' . _PROJETO_AUTORIA . '<br>
                ' . _PROJETO_TITULO . '<br>
                ' . _PROJETO_WEBSITELINK ;

            // Envia
            if( !EmailEnviar( $cEMailDestino, $cEMailAssunto, $cEMailTituloMensagem, $cEMailMensagem, $this -> sisConfig["email_servidor"] ) ){
                
                $this -> ReportaFalha(null,'Mensagem não enviada');
                
            }else{
                
                $this -> ReportaSucesso( "se tiver informado o endereço de e-mail, corretamente, 
                    dentro de 5 minutos, você receberá uma mensagem contendo 
                    instruções.", "Usuário cadastrado, com sucesso!", "?c=m&a=sd" );

            }

        }else{

            $this -> ReportaFalha(null,null);

        }

    }

    public function AtualizaUsuario( $id_usuario ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso("Operacional");
        
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
            //$this -> ReportaSucesso('seu usuário de acesso foi atualizado, com sucesso!',null,'?c=u&a=l');
            header("Location: index.php?c=u&a=l");
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

        negarAcesso("Administrativo");
        
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