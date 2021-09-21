<?php

class ConexaoClass{
    
    var $oConexaoInterna;

    public function openConnect($bd){

        $this -> oConexaoInterna = new mysqli( $bd["hostname"], $bd["username"], $bd["password"], $bd["database"] );
        if( $this -> oConexaoInterna -> connect_error ){
            exit( "Conexão com o banco de dados " . $bd["database"] . " falhou: " . $this -> oConexaoInterna -> error );
        }
        $this -> oConexaoInterna -> set_charset('utf8');
    }

    public function getConnect(){
        return $this -> oConexaoInterna;
    }

    public function closeConnect(){

        if( isset( $this -> oConexaoInterna ) ){
            $this -> oConexaoInterna -> close();
        }

    }

}