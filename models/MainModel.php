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

    public function consultaPerfil( $clienteId ){

        $sql = "SELECT * 
        FROM tb_perfil 
        WHERE id_perfil = $clienteId";

        $this -> resultado = $this -> Conn -> query( $sql );
    
    }

    public function getConsult(){
        return $this -> resultado;
    }

}
?>