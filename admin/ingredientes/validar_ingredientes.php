<?php
include("../../conexion.php");

$resultado = mysqli_query($conexion, "SELECT COUNT(*) as total FROM ingredientes");
$fila = mysqli_fetch_assoc($resultado);

if ($fila['total'] > 0) {
    echo "OK";
} else {
    echo "NO_INGREDIENTES";
}
?>
