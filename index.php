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
                        case 'd': $PerfilCTRL -> RemovePerfil($_GET['id']); break;
                        case 'da': $PerfilCTRL -> RemovePerfilAction(); break;
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
                        case 'vna': $MainCTRL -> AcessoNegado($_GET['nivel']); break;
                        case 'sd': $MainCTRL -> DestroySession(); break;
                        case 'pedreserv': $MainCTRL -> ReservarPedido( $_GET['id'] ); break;
                        case 'peddes': $MainCTRL -> DesistirPedido( $_GET['id'] ); break;
                    }
                }
            break;
            
            case 'p':
                
                require_once("controllers/PedidosController.php");
                $PedidoCTRL = new PedidosController($sisConfig);
                if( ! isset( $_GET['a'] ) ){
                    $PedidoCTRL -> Index();
                }
                else
                {
                    switch( $_REQUEST['a'] ){
                        case 'l': $PedidoCTRL -> ListaPedidos(); break;
                        case 'i': $PedidoCTRL -> InserePedido(); break;
                        case 'ia': $PedidoCTRL -> InserePedidoAction(); break;
                        case 'u': $PedidoCTRL -> AtualizaPedido($_GET['id']); break;
                        case 'ua': $PedidoCTRL -> AtualizaPedidoAction(); break;
                        case 'd': $PedidoCTRL -> RemovePedido( $_GET['id'] ); break;
                        case 'canc': $PedidoCTRL -> CancelarPedido( $_GET['id'] ); break;
                        case 'reserv': $PedidoCTRL -> ReservarPedido( $_GET['id'] ); break;
                        case 'des': $PedidoCTRL -> DesistirPedido( $_GET['id'] ); break;
                        case 'fin': $PedidoCTRL -> FinalizarPedido( $_GET['id'] ); break;
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

        $nivelExigido = mb_convert_case( $nivelExigido,  MB_CASE_TITLE, 'UTF-8' );
        if(isset( $_SESSION[ "usuarioNomeLogin" ] )){

            if( $_SESSION['nivel'] < _USUARIOS_LISTA_NIVEIS["$nivelExigido"] ){
                return false;
            }else{
                return true;
            }

        }else{
            return false;
        }

    }

    function negarAcesso( $nivelExigido ){

        $nivelExigido = mb_convert_case( $nivelExigido,  MB_CASE_TITLE, 'UTF-8' );
        if(isset( $_SESSION[ "usuarioNomeLogin" ] )){

            if( $_SESSION['nivel'] < _USUARIOS_LISTA_NIVEIS["$nivelExigido"] ){
                header("Location: index.php?c=m&a=vna&nivel="._USUARIOS_LISTA_NIVEIS["$nivelExigido"]);
                exit;
            }

        }else{
            return false;
        }

    }

?>