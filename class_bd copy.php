<?php
class bd{
    private $host;
    private $conexion;
    private $bd;
    private $usuario;
    private $clave;
    private $tipo;
    private $connectionOptions;
    
    function __construct(){
     
        $this->host="10.51.129.29";
        $this->bd="FullstarV3_data";
        $this->usuario="cellstaradm";
        $this->clave="cellstaradm";
        $this->tipo="sqlsrv";
        $this->connectionOptions=[
            "database" => $this->bd,
            "uid" =>$this->usuario,
            "pwd" =>$this->clave,

        ];

        


        $this->conexion=sqlsrv_connect($this->host,  $this->connectionOptions) or die (sqlsrv_errors());
      //  mysqli_set_charset($this->conexion, 'utf8');


      if( $this->conexion ) {
      //  echo "Conexión establecida.<br />";
   }else{
        echo "Conexión no se pudo establecer.<br />";
        die( print_r( sqlsrv_errors(), true));
   }
     
        }

    function query($sql){
        $query=sqlsrv_query($this->conexion,$sql) or die("Error Sql: ".$sql);
        return $query;
        }

    function num_rows($sql){
        $query=$this->query($sql);
        $num_rows=sqlsrv_num_rows($query);
        return $num_rows;


        } 
    function fetch_array($sql){

        $query=$this->query($sql);
        while ($row = sqlsrv_fetch_array($query)){
            $resultado[]=$row ;  
        };
        return $resultado;
        }
    function fetch_object($sql){
        $query=$this->query($sql);

        while ($row = sqlsrv_fetch_object($query)){
            $resultado[]=$row ;  
        };
        return $resultado;
        }
    function free_result($sql){
        $query=$this->query($sql);
        return sqlsrv_free_stmt($query);
        }
    function cerrar(){
        sqlsrv_close($this->conexion);
        }            
    }   
