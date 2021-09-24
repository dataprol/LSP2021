<?php
    
    require_once("config/config.php");
    session_start();    
    
    if( ! isset( $_GET['c'] ) ){

        require_once("controllers/MainController.php");
        $MainCTRL = new MainController($sisConfig);
        $MainCTRL -> Index();

    }
    else
    {
        switch( $_REQUEST['c'] ){
            
            case 'c':
                
                require_once("controllers/PerfisController.php");
                $PerfilCTRL = new PerfisController($sisConfig);
                if( ! isset( $_GET['a'] ) ){
                    $PerfilCTRL -> Index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'i': $PerfilCTRL -> InserePerfil(); break;
                        case 'ia': $PerfilCTRL -> InserePerfilAction(); break;
                        case 'u': $PerfilCTRL -> AtualizaPerfil($_GET['id']); break;
                        case 'ua': $PerfilCTRL -> AtualizaPerfilAction(); break;
                    }
                }
            break;

            case 'm':
            
                require_once("controllers/MainController.php");
                $MainCTRL = new MainController($sisConfig);
                
                if( ! isset( $_GET['a'] ) ){
                    $MainCTRL -> Index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'i': $MainCTRL -> Index(); break;
                        case 'l': $MainCTRL -> Login(); break;
                        case 'vna': $MainCTRL -> AcessoNegado(); break;
                        case 'sd': $MainCTRL -> DestroySession(); break;
                    }
                }
            break;
            
            case 'u':
        
                require_once("controllers/UsuariosController.php");
                $UsuarioCTRL = new UsuariosController($sisConfig);
                if( ! isset( $_GET['a'] ) ){
                        $UsuarioCTRL -> Index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'vl': $UsuarioCTRL -> ValidaLogin(); break;
                        case 'u': $UsuarioCTRL -> AtualizaUsuario($_GET['id']); break;
                        case 'ua': $UsuarioCTRL -> AtualizaUsuarioAction(); break;
                        case 'cp': $UsuarioCTRL -> TrocaDeSenha($_GET['id']); break;
                        case 'vcp': $UsuarioCTRL -> ValidaTrocaDeSenha(); break;
                        case 'fp': $UsuarioCTRL -> EsqueceuSenha(); break;
                        case 'fpa': $UsuarioCTRL -> EsqueceuSenhaAction(); break;
                        case 'l': $UsuarioCTRL -> ListaUsuarios(); break;
                        case 'i': $UsuarioCTRL -> InsereUsuario(); break;
                        case 'ia': $UsuarioCTRL -> InsereUsuarioAction(); break;
                        case 'r': $UsuarioCTRL -> ReiniciaSenha($_GET['id']); break;
                        case 'd': $UsuarioCTRL -> RemoveUsuario( $_GET['id'] ); break;
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