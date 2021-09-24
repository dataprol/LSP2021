<?php

    setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
    date_default_timezone_set( 'America/Sao_Paulo' );

    // Prazo para a sessão expirar:
    // Talvez, deixar até 21600 minutos que são 15 dias. 
    // Cuidado! Dados ficarão gravados por todo esse tempo!
    // Risco de sobrecarga e de falha de segurança!
    //session_cache_expire(1);

    // Configurações da instalação no servidor
    require_once('cfgsna.php');

    // Detecta se está em produção ou desenvolvimento/teste e faz ajustes
    if( $_SERVER['SERVER_NAME'] == 'localhost' ){

        $sisConfig['modo_de_trabalho'] = 'dev';

        // Banco de dados
        $sisConfig['banco_de_dados'] = $bd_local;

        // Locais de arquivos
        $sisConfig["arquivos"] = "assets/arquivos";
        $sisConfig['imagens'] = "assets/img";

        // Exibir todos os detalhes de erros
        ini_set( 'display_errors', 1 );
        ini_set( 'display_startup_errors', 1 );
        error_reporting( E_ALL );

    }else{

        $sisConfig['modo_de_trabalho'] = 'prod';

        // Banco de dados
        $sisConfig['banco_de_dados'] = $bd_remoto;

        // Locais de arquivos
        $sisConfig['arquivos'] = "assets/arquivos";
        $sisConfig['imagens'] = "assets/img";

        // Ocultar detalhes dos erros
        ini_set( 'display_errors', 0 );
        ini_set( 'display_startup_errors', 0 );
        error_reporting( E_ERROR );

    }

    // Diversos
    $sisConfig['autoria'] = '2021 - Luiz Carlos Costa Rodrigues e Geraldo Samir Silveira Varriale, disciplina de LSP, ULBRA, Torres/RS';
    $sisConfig['tipos_de_cadastro'] = array("Comércio","ONG");
    $sisConfig['versão'] = "0.1.0.3";

?>