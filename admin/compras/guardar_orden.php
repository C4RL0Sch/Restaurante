<?php
include("../../usuario.php");
include("../../conexion.php");
session_start();
$usuario = $_SESSION['usuario'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProveedor = $_POST['idProveedor'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $ingredientes = $_POST['ingredientes'] ?? [];
    $cantidades = $_POST['cantidades'] ?? [];
    $costos = $_POST['costos'] ?? [];

    if (!$idProveedor || !$fecha || count($ingredientes) === 0) {
        echo "Datos incompletos";
        exit;
    }

    $queryOrden = "INSERT INTO ordenes_compra (idUsuario, idProveedor, Fecha) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($queryOrden);
    $stmt->bind_param("iis", $usuario->id, $idProveedor, $fecha);
    $stmt->execute();
    $idOrden = $stmt->insert_id;
    $stmt->close();

    $queryDetalle = "INSERT INTO detalle_compra (idOrden, idIngrediente, Cantidad, CostoUnitario) VALUES (?, ?, ?, ?)";
    $stmtDetalle = $conexion->prepare($queryDetalle);

    for ($i = 0; $i < count($ingredientes); $i++) {
        $idIng = $ingredientes[$i];
        $cantidad = floatval($cantidades[$i]);
        $costo = floatval($costos[$i]);

        $stmtDetalle->bind_param("iidd", $idOrden, $idIng, $cantidad, $costo);
        $stmtDetalle->execute();

        $conexion->query("UPDATE ingredientes SET Cantidad = Cantidad + $cantidad WHERE idIngrediente = $idIng");
    }

    $stmtDetalle->close();

    echo "Compra registrada correctamente";
} else {
    echo "Método no permitido";
}