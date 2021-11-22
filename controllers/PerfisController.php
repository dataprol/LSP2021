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
        require_once("views/perfis/InserePerfil.php");
        require_once("views/footer.php");
        
    }

    public function InserePerfilAction(){
        
        $novaSenha = $this -> GeraSenha( 8, true, true, true, true );
        $prazoDias = 1;
        $validadeSenha = new DateTime();
        $prazoSenha = new DateInterval( "P" . $prazoDias . "D" );
        $validadeSenha -> add( $prazoSenha );

        // Confere se login já está em uso
        $this -> PerfilModel -> consultaUsuarioLogin( mb_convert_case( $_POST["usuarioLogin"],  MB_CASE_TITLE, 'UTF-8' ) );
        $arrayUsuarios = $this -> PerfilModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReportaFalha("já existe usuário com o mesmo nome de usuário informado","Inconsistência");
            exit;

        }

        // Confere se e-mail já está em uso
        $this -> PerfilModel -> consultaUsuarioEmail( strtolower($_POST["perfilEmail"]) );
        $arrayUsuarios = $this -> PerfilModel -> ObtemConsulta() -> fetch_assoc();
        if( !empty( $arrayUsuarios ) ){

            $this -> ReportaFalha("já existe usuário com o mesmo e-mail informado","Inconsistência");
            exit;

        }

        // Pega o índice do campo de tipo de cadastro
        $_POST["perfilTipo"] = array_search( $_POST["perfilTipo"], _TIPOS_DE_CADASTRO ); 
        
        // Grava o novo pefil
        $this -> PerfilModel -> InserePerfil( $_POST );
        
        // Pega o Id do novo perfil, para armazenar na tabela de usuário
        $result = $this -> PerfilModel -> ObtemConsulta();

        if( $result != false ){
            
            // Sucesso!
            $arrayUsuarios['perfilId'] = $result;
            $arrayUsuarios['usuarioNivel'] = 99;
            $arrayUsuarios['usuarioLogin'] = $_POST['usuarioLogin'];
            //$arrayUsuarios['usuarioSenha'] = md5( $_POST['usuarioSenha'] );
            $arrayUsuarios['usuarioSenha'] = md5( $novaSenha );
            $arrayUsuarios['usuarioEmail'] = $_POST['perfilEmail'];
            $arrayUsuarios['usuarioTelefoneCelular'] = $_POST['perfilTelefone'];
            //$arrayUsuarios["usuarioSenhaValidade"] = $validadeSenha -> format( "Y-m-d" );
            
            // Cadastra o usuário
            $this -> PerfilModel -> InsereUsuario($arrayUsuarios);
            $result = $this -> PerfilModel -> ObtemConsulta();
            if( $result != false ){

                // Sucesso!
                $usuarioId = $result;

                // Mensagem por e-mail:
                require_once("email.php");
            
                // Prepara mensagem
                $cEMailDestino = [$arrayUsuarios["usuarioEmail"], $arrayUsuarios["usuarioLogin"]];
                $cEMailAssunto = "Boas vindas do " . _PROJETO_TITULO;
                $cEMailTituloMensagem = "Bem vindo(a) ao " . _PROJETO_TITULO;
                $cTextoNovaSenha = '
                    <p>

                        Usuário: <b>' . $arrayUsuarios["usuarioLogin"] . '</b><br>
                        Senha provisória: <b>' . $novaSenha . '</b><br>
                        <b>Validade: ' . $prazoDias * 24 . ' horas</b>
                    </p>';
                $cEMailMensagem = '
                    <h1>' . $cEMailTituloMensagem . '</h1>
                    <h3>Seu perfil e seu usuário de acesso foram criados, com sucesso! Confira, a seguir!</h3>
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
                    
                    $this -> ReportaFalha('seu cadastro foi concluído com sucesso! Apesar disso, houve falha noenvio de uma mensagem para seu e-mail. Por favor, entre em contato conosco.','Mensagem não enviada','?c=m&a=sd');

                }else{
                    
                    $this -> ReportaSucesso( "seu cadastro foi concluído com sucesso! 
                        Se tiver informado seu e-mail, corretamente, dentro de 5 minutos, 
                        você receberá uma mensagem contendo instruções.", "Parabéns! Cadastro concluído!", "?c=m&a=sd" );

                }                

            }else{
                $this -> ReportaFalha('houve uma falha na tentativa de gravação do usuário para seu perfil!',null);
            }

        }else{
            $this -> ReportaFalha('houve uma falha na tentativa de gravação de seu perfil!',null);
        }

    }

    public function AtualizaPerfil( $id_perfil ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso("Administrativo");

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
        $_POST["perfilTipo"] = array_search( $_POST["perfilTipo"], _TIPOS_DE_CADASTRO ); 

        $this -> PerfilModel -> AtualizaPerfil($_POST);
        if( $this -> PerfilModel -> ObtemConsulta() != false ){            
            $this -> ReportaSucesso("perfil atualizado, com sucesso!",null,"?c=m&a=i");
        }else{
            $this -> ReportaFalha('houve uma falha na tentativa de atualização de seu perfil!',null);
        }        
        
    }

    public function RemovePerfil( $id_perfil ){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso("Administrativo");

        $this -> PerfilModel -> ConsultaPerfil( $id_perfil );
        $result = $this -> PerfilModel -> ObtemConsulta();
        if( $arrayPerfil = $result -> fetch_assoc() ){
            require_once("views/header.php");
            require_once("views/perfis/ExcluiPerfil.php");
            require_once("views/footer.php");
        }else{
            $this -> ReportaFalha(null,null);
        }

    }

    public function RemovePerfilAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso("Administrativo");
        
        $this -> PerfilModel -> ConsultaPedidosReservados($_SESSION["id_perfil"]);
        $result = $this -> PerfilModel -> ObtemConsulta();
        if( $result->num_rows == 0 ){
            $this -> PerfilModel -> RemovePerfil( $_SESSION["id_perfil"] );
            $result = $this -> PerfilModel -> ObtemConsulta();
            if( $result != false ){
                $this -> ReportaSucesso("perfil removido, com sucesso!",null,"?c=m&a=sd");
            }else{
                if( $this -> PerfilModel -> Conn -> errno == 1451 and $this -> PerfilModel -> Conn -> sqlstate == 23000 ){
                    $cErro = "<br>É necessário remover todos os usuários e doações, antes. 
                    Mas, alguma restrição está impedindo o processo.";
                }
                $this -> ReportaFalha("a tentativa de remoção falhou!$cErro",null);
            } 
        }else{
            $cErro = "<br>É necessário remover todos os usuários e doações, antes. 
            Mas, alguma doação está impedida de ser excluída por ter sido reservada. Por favor revise.";
            $this -> ReportaFalha("a tentativa de remoção falhou!$cErro",null);
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