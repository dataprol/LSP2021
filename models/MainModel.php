<?php
class MainModel{
    
    var $resultado;
    var $conn;

    function __construct($bd){
        
        require_once("bd/ConexaoClass.php");
        $oConexao = new ConexaoClass();
        $oConexao -> openConnect($bd);
        $this -> Conn = $oConexao -> getConnect();

    }

    public function ConsultaPerfil( $clienteId ){

        $sql = "SELECT * 
        FROM tb_perfil 
        WHERE id_perfil = $clienteId";

        $this -> resultado = $this -> Conn -> query( $sql );
    
    }

    public function ListaPedidos($perfilId){
        
        $where = "";        
        if( $perfilId != null ){
            $where = "WHERE fk_id_perfil_pedido = $perfilId";
        }

        $sql = "SELECT * FROM tb_pedido 
                $where
                LIMIT 50";
                //ORDER BY usuarioNome LIMIT 25";

        $this -> resultado = $this -> Conn -> query( $sql );

    }
    public function ObtemConsulta(){
        return $this -> resultado;
    }

}
?>