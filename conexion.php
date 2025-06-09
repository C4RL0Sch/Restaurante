<?php
$host = "localhost";
$usuario = "root";
$clave = "12345678";
$base_datos = "restaurante";

$conexion = mysqli_connect($host, $usuario, $clave, $base_datos);

if (!$conexion){
   	die("Falló la conexión ".mysqli_connect_error());
}