<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroFactura = $_POST['numero_factura'];
    $fecha = $_POST['fecha'];
    $cliente = $_POST['cliente'];
    $productos = $_POST['productos'];
    $montos = $_POST['montos'];

    $sql = "INSERT INTO facturas (numero_factura, fecha, cliente) VALUES ('$numeroFactura', '$fecha', '$cliente')";
    echo "La factura se ha creado exitosamente.";
}
?>
