<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "restaurante";

$conexion = new mysqli($host, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}