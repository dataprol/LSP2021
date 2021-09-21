<?php
    
    require_once("config/config.php");
    session_start();    
    
    if( ! isset( $_GET['c'] ) ){

        require_once("controllers/MainController.php");
        $MainCTRL = new MainController($ConfigSis);
        $MainCTRL -> index();

    }
    else
    {
        switch( $_REQUEST['c'] ){
            
            case 'c':
                
                require_once("controllers/PerfisController.php");
                $PerfilCTRL = new PerfisController($ConfigSis);
                if( ! isset( $_GET['a'] ) ){
                    $PerfilCTRL -> index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'i': $PerfilCTRL -> inserePerfil(); break;
                        case 'ia': $PerfilCTRL -> inserePerfilAction(); break;
                        case 'u': $PerfilCTRL -> atualizaPerfil($_GET['id']); break;
                        case 'ua': $PerfilCTRL -> atualizaPerfilAction(); break;
                    }
                }
            break;

            case 'm':
            
                require_once("controllers/MainController.php");
                $MainCTRL = new MainController($ConfigSis);
                
                if( ! isset( $_GET['a'] ) ){
                    $MainCTRL -> index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'i': $MainCTRL -> index(); break;
                        case 'l': $MainCTRL -> login(); break;
                        case 'vna': $MainCTRL -> acessoNegado(); break;
                        case 'sd': $MainCTRL -> destroySession(); break;
                    }
                }
            break;
            
            case 'u':
        
                require_once("controllers/UsuariosController.php");
                $UsuarioCTRL = new UsuariosController($ConfigSis);
                if( ! isset( $_GET['a'] ) ){
                        $UsuarioCTRL -> index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'vl': $UsuarioCTRL -> validaLogin(); break;
                        case 'fp': $UsuarioCTRL -> esqueceuSenha(); break;
                        case 'fpa': $UsuarioCTRL -> esqueceuSenhaAction(); break;
                        case 'cp': $UsuarioCTRL -> trocaDeSenha($_GET['id']); break;
                        case 'vcp': $UsuarioCTRL -> validaTrocaDeSenha(); break;
                        case 'r': $UsuarioCTRL -> reiniciaSenha($_GET['id']); break;
                        case 'i': $UsuarioCTRL -> insereUsuario(); break;
                        case 'ia': $UsuarioCTRL -> insereUsuarioAction(); break;
                        case 'u': $UsuarioCTRL -> atualizaUsuario($_GET['id']); break;
                        case 'ua': $UsuarioCTRL -> atualizaUsuarioAction(); break;
                        case 'l': $UsuarioCTRL -> listaUsuarios(); break;
                        case 'd': $UsuarioCTRL -> removeUsuario( $_GET['id'] ); break;
                    }
                }
            break;    
        }
    }

    function verificaNivelAcesso( $nivelExigido ){

        if(isset( $_SESSION[ "usuarioNomeLogin" ] )){

            if( $_SESSION['nivel'] < $nivelExigido ){
                return false;
            }else{
                return true;
            }

        }else{
            return false;
        }

    }

    function negarAcesso( $nivelExigido ){

        if(isset( $_SESSION[ "usuarioNomeLogin" ] )){

            if( $_SESSION['nivel'] < $nivelExigido ){
                header("Location: index.php?c=m&a=vna");
                exit;
            }

        }else{
            return false;
        }

    }

?>