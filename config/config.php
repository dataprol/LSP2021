<?php

	//session_cache_expire(2880); 	// 21600 minutos são 15 dias. 
									// Cuidado! Dados ficarão gravados por todo esse tempo!
                                    // Risco de sobrecarga e de falha de segurança!
                           
    //setlocale(LC_ALL , 'pt_BR');
    setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
    date_default_timezone_set( 'America/Sao_Paulo' );

    $ConfigSis['autoria'] = '2021 - Luiz Carlos Costa Rodrigues e Geraldo Samir Silveira Varriale, disciplina de LSP, ULBRA, Torres/RS';
    
    // Inicializa variáveis
    $ConfigSis['tipos_de_cadastro'] = array("Comércio","ONG");
    
    // Detecta se está em produção ou desenvolvimento/teste e faz ajustes
    if( $_SERVER['SERVER_NAME'] == 'localhost' ){

        $ConfigSis['modo_de_trabalho'] = 'dev';
        
        // Banco de dados
        $bd = array('hostname' => 'localhost', 
                    'username' => 'testadorLSP',
                    'password' => 'lsp2021',
                    'database' => 'lsp');
        
        $ConfigSis['banco_de_dados'] = $bd;

        // Arquivos
        $ConfigSis["websitepath"] = '/';
        $ConfigSis["adminpath"] = '/';
        $ConfigSis["arquivos"] = "assets/arquivos";
        $ConfigSis['imagens'] = "assets/img";

        // Erros
        ini_set( 'display_errors', 1 );
        ini_set( 'display_startup_errors', 1 );
        error_reporting( E_ALL );

    }else{

        $ConfigSis['modo_de_trabalho'] = 'prod';

        // Banco de dados
        $bd = array('hostname' => 'xxxxxx.mysql.dbaas.com.br', 
                    'username' => 'xxxxxx',
                    'password' => 'xxxxxx',
                    'database' => 'xxxxxx');

        $ConfigSis['banco_de_dados'] = $bd;

        // Arquivos
        $ConfigSis["websitepath"] = '/';
        $ConfigSis["adminpath"] = '/';
        $ConfigSis['arquivos'] = "arquivos";
        $ConfigSis['imagens'] = "img";
        
        // Erros
        ini_set( 'display_errors', 0 );
        ini_set( 'display_startup_errors', 0 );
        error_reporting( E_ERROR );
      
    }

?>