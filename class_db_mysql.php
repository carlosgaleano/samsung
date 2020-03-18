<?php
class bd{
    private $host;
    private $conexion;
    private $bd;
    private $usuario;
    private $clave;
    private $tipo;
    
    function __construct(){
        global $bdhost;
        global $bdnombre;
        global $bdusuario;
        global $bdclave;
        $this->host="localhost";
        $this->bd="mnonline";
        $this->usuario="carlos";
        $this->clave="Cags528$";
        $this->tipo="mysqli";
        $this->conexion=mysqli_connect($this->host, $this->usuario, $this->clave) or die (mysqli_error());
        mysqli_set_charset($this->conexion, 'utf8');
        mysqli_select_db($this->conexion, $this->bd ) or die("Error en la selección de la base de datos");
        }

    function query($sql){
        $query=mysqli_query($this->conexion,$sql) or die("Error MySql: ".mysqli_error()." ".$sql);
        return $query;
        }

    function num_rows($query){
        $num_rows=mysqli_num_rows($query);
        return $num_rows;
        } 
    function fetch_array($query){
        $fila=mysqli_fetch_array($query);
        return $fila;
        }
    function fetch_object($query){
        $fila=mysqli_fetch_object($query);
        return $fila;
        }
    function free_result($query){
        mysqli_free_result($query);
        }
    function cerrar(){
        mysqli_close();
        }            
    }   

?>