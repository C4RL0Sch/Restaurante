<?php
require_once("../../conexion.php");

$resultado = mysqli_query($conexion, "SELECT COUNT(*) as total FROM proveedores");
$fila = mysqli_fetch_assoc($resultado);

if ($fila['total'] > 0) {
    echo "OK";
} else {
    echo "NO_PROVEEDORES";
}