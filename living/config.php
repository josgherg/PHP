<?php

function conectar(){
    $servername ="localhost";
    $username = "root"; 
    $password = "";
    $database = "rsocial";
    $con=new mysqli($servername,$username,$password,$database);
    if($con->connect_error){
        die("Error de conexion a la base de adtos: ". $con->connect_error);
    }
    //echo "conexion exitosa";
    return $con;
}

?>