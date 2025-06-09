<?php
include("../../conexion.php");

$res = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM ordenes_compra");
$fila = mysqli_fetch_assoc($res);

echo $fila['total'] > 0 ? "1" : "0";