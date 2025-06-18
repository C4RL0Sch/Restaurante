<?php
include("../../conexion.php");
$id = intval($_GET['id']);
$ord = mysqli_fetch_assoc(mysqli_query($conexion,
  "SELECT c.Fecha,u.Nombre AS Chef 
   FROM consumo c
   JOIN usuarios u ON c.idUsuario=u.idUsuario
   WHERE c.idConsumo=$id"
));
echo "<p><b>Chef:</b> {$ord['Chef']}<br><b>Fecha:</b> {$ord['Fecha']}</p>";
echo "<table class='table'><thead><tr><th>Ingrediente</th><th>Cantidad</th></tr></thead><tbody>";
$res = mysqli_query($conexion,
  "SELECT i.Nombre,d.Cantidad,i.Medida 
   FROM detalle_consumo d
   JOIN ingredientes i ON d.idIngrediente=i.idIngrediente
   WHERE d.idConsumo=$id"
);
while($r=mysqli_fetch_assoc($res)){
  $cantidad = $r['Cantidad'];
  echo "<tr><td>{$r['Nombre']}</td><td>$cantidad {$r['Medida']}</td></tr>";
}
echo "</tbody></table>";
