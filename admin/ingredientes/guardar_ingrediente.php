<?php
require_once("../../conexion.php");

$id = trim($_POST['id']) ?? '';
$nombre = trim($_POST['nombre']) ?? '';
$medida = trim($_POST['medida']) ?? '';
$cantidad = trim($_POST['cantidad']) ?? '';
$costo = trim($_POST['costo']) ?? '';
$idProveedor = trim($_POST['idProveedor']) ?? '';

if ($nombre === '' || $medida === '' || $cantidad === '' || $costo === '' || $idProveedor === '') {
    echo "Todos los campos son obligatorios.";
    exit();
}

$existe = mysqli_query($conexion, "SELECT * FROM ingredientes WHERE idIngrediente=$id");
if (mysqli_num_rows($existe) > 0) {
    exit("El ID ya esta ocupado.");
}

$existe = mysqli_query($conexion, "SELECT * FROM ingredientes WHERE Nombre = UPPER('$nombre');");
if (mysqli_num_rows($existe) > 0) {
    exit("El ingrediente ya está registrado.");
}

$stmt = $conexion->prepare("INSERT INTO ingredientes (idIngrediente, Nombre, Medida, Cantidad, Costo, idProveedor) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issddi", $id, $nombre, $medida, $cantidad, $costo, $idProveedor);

if ($stmt->execute()) {
    echo "✅ Ingrediente registrado.";
} else {
    echo "❌ Error al guardar.";
}
$stmt->close();