<?php
include("../../usuario.php");
include("../../conexion.php");
session_start();
$usr = $_SESSION['usuario']->id;

$ings = $_POST['ingredientes'] ?? [];
$cant = $_POST['cantidades'] ?? [];

if (!count($ings)) exit("Debes agregar al menos un ingrediente.");

$q = $conexion->prepare("INSERT INTO consumo (idUsuario, Fecha) VALUES (?, NOW())");
$q->bind_param("i",$usr);
$q->execute();
$idC = $q->insert_id;
$q->close();

$p = $conexion->prepare("INSERT INTO detalle_consumo (idConsumo,idIngrediente,Cantidad) VALUES (?,?,?)");
for($i=0;$i<count($ings);$i++){
  $c = floatval($cant[$i]);
  $p->bind_param("iid",$idC,$ings[$i],$c);
  $p->execute();
  
  $conexion->query("UPDATE ingredientes SET Cantidad = Cantidad - $c WHERE idIngrediente = {$ings[$i]}");
}
$p->close();

echo "Consumo registrado correctamente.";