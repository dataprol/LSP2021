<?php

    setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
    date_default_timezone_set( 'America/Sao_Paulo' );

    // Configurações da instalação no servidor
    require_once('cfgsna.php');

    // Prazo para a sessão expirar:
    // Talvez, deixar até 21600 minutos que são 15 dias. 
    // Cuidado! Dados ficarão gravados por todo esse tempo!
    // Risco de sobrecarga e de falha de segurança!
    //session_cache_expire(1);

    // Detecta se está em produção ou desenvolvimento/teste e faz ajustes
    if( $_SERVER['SERVER_NAME'] == 'localhost' 
    or $_SERVER['SERVER_NAME'] == '127.0.0.1' 
    or $_SERVER['SERVER_NAME'] == '192.168.0.105' 
    or $_SERVER['SERVER_NAME'] == '10.0.0.1' ){

        // Sinaliza modo de desenvolvimento/teste
        define( "_MODO_DE_TRABALHO", "dev" );

        // Exibir todos os detalhes de erros
        ini_set( 'display_errors', 1 );
        ini_set( 'display_startup_errors', 1 );
        error_reporting( E_ALL );

        // Banco de dados
        $sisConfig['banco_de_dados'] = $bd_local;

        // Servidor de email
        $sisConfig['email_servidor'] = $mail_server;

        // Locais de arquivos
        $sisConfig["arquivos"] = "assets/arquivos";
        $sisConfig['imagens'] = "assets/img";

    }else{

        // Sinaliza modo de produção/online
        define( "_MODO_DE_TRABALHO", "prod");

        // Ocultar detalhes dos erros
        ini_set( 'display_errors', 1 );
        ini_set( 'display_startup_errors', 1 );
        error_reporting( E_ERROR );

        // Banco de dados
        $sisConfig['banco_de_dados'] = $bd_remoto;

        // Servidor de email
        $sisConfig['email_servidor'] = $mail_server;

        // Locais de arquivos
        $sisConfig['arquivos'] = "assets/arquivos";
        $sisConfig['imagens'] = "assets/img";

    }

    // Cria as constantes gerais do sistema
    define( "_PROJETO_TITULO", "Prato Solidário" );
    define( "_PROJETO_VERSAO", "0.1.5.1" );
    define( "_PROJETO_AUTORIA", "Geraldo Samir Silveira Varriale e Luiz Carlos Costa Rodrigues" );
    define( "_PROJETO_COPYRIGHT", "2021 - Geraldo Samir Silveira Varriale e Luiz Carlos Costa Rodrigues" );
    define( "_PROJETO_WEBSITELINK", '<a href="https://lsp.provisorio.ws/">lsp.provisorio.ws</a>');
    define( "_PROJETO_WEBSITEURL", 'https://lsp.provisorio.ws/');
    define( "_FMT_DATA_HORA", "%d/%m/%Y %Hh%M" );
    define( "_TIPO_COMERCIO", 0 );
    define( "_TIPO_ONG", 1 );
    define( "_USUARIOS_LISTA_NIVEIS", array("Operacional"=>24,
                                            "Técnico"=>49,
                                            "Gerencial"=>74,
                                            "Administrativo"=>99) );
    define( "_TIPOS_DE_CADASTRO", array("Comércio","ONG") );
    define( "_PEDIDOS_SITUACOES", array("Disponível"=>0,
                                        "Reservada"=>1,
                                        "Cancelada"=>8,
                                        "Coletada"=>9) );

?>