<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroFactura = $_POST['numero_factura'];
    $fecha = $_POST['fecha'];
    $cliente = $_POST['cliente'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];

    $montoTotal = 0;
    for ($i = 0; $i < count($productos); $i++) {
        $cantidad = $cantidades[$i];
        $precio = $precios[$i];
        $subtotal = $cantidad * $precio;
        $montoTotal += $subtotal;
    }

    $sql = "INSERT INTO facturas (numero_factura, fecha, cliente, monto_total) 
            VALUES ('$numeroFactura', '$fecha', '$cliente', '$montoTotal')";
    
    echo "La factura se ha creado exitosamente.";
}
?>
