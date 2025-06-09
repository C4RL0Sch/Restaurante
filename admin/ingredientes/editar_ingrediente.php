<?php
require_once("../../conexion.php");

$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$costo = $_POST['costo'] ?? '';
$medida = $_POST['medida'] ?? '';
$idProveedor = $_POST['idProveedor'] ?? '';

if ($id === '' || $nombre === '' || $cantidad === '' || $costo === '' || $medida === '' || $idProveedor === '') {
    echo "Todos los campos son obligatorios.";
    exit();
}

$existe = mysqli_query($conexion, "SELECT * FROM ingredientes WHERE Nombre = UPPER('$usuario') AND idIngrediente != $id");
if (mysqli_num_rows($existe) > 0) {
    exit("El nombre del ingrediente ya está en uso.");
}

$stmt = $conexion->prepare("UPDATE ingredientes SET Nombre = ?, Cantidad = ?, Costo = ?, Medida = ?, idProveedor = ? WHERE idIngrediente = ?");
$stmt->bind_param("sddsii", $nombre, $cantidad, $costo, $medida, $idProveedor, $id);

if ($stmt->execute()) {
    echo "✅ Ingrediente actualizado.";
} else {
    echo "❌ Error al actualizar.";
}
$stmt->close();