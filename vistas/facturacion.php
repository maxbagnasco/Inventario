<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroFactura = $_POST['numero_factura'];
    $fecha = $_POST['fecha'];
    $cliente = $_POST['cliente'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];

    $montoTotal = 0;
    foreach ($productos as $index => $producto) {
        $cantidad = $cantidades[$index];
        $precio = $precios[$index];
        $subtotal = $cantidad * $precio;
        $montoTotal += $subtotal;
    }

    $servername = "nombre_servidor";
    $username = "nombre_usuario";
    $password = "contraseña";
    $dbname = "nombre_base_de_datos";

    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    $numeroFactura = mysqli_real_escape_string($conn, $numeroFactura);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $cliente = mysqli_real_escape_string($conn, $cliente);
    $montoTotal = mysqli_real_escape_string($conn, $montoTotal);

    $sql = "INSERT INTO facturas (numero_factura, fecha, cliente, monto_total) 
            VALUES ('$numeroFactura', '$fecha', '$cliente', '$montoTotal')";

    if ($conn->query($sql) === TRUE) {
        echo "La factura se ha creado exitosamente.";
    } else {
        echo "Error al crear la factura: " . $conn->error;
    }

   $conn->close();
}
?>

<!-- VER QUE MODIFICAR EN ESTE SECTOR DEL HTML -->

<!DOCTYPE html>
<html>
<head>
    <title>Facturación</title>
</head>
<body>
    <form method="POST" action="">
        <label for="numero_factura">Número de factura:</label>
        <input type="text" name="numero_factura" id="numero_factura" required>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>

        <label for="cliente">Cliente:</label>
        <input type="text" name="cliente" id="cliente" required>

        <h3>Productos:</h3>
        <div id="productos-container">
            <div class="producto">
                <input type="text" name="productos[]" placeholder="Nombre del producto" required>
                <input type="number" name="cantidades[]" placeholder="Cantidad" required>
                <input type="number" name="precios[]" placeholder="Precio unitario" required>
            </div>
        </div>
        <button type="button" id="agregar-producto">Agregar producto</button>

        <button type="submit">Crear factura</button>
    </form>

    <script>
        document.getElementById('agregar-producto').addEventListener('click', function() {
            var container = document.getElementById('productos-container');
            var productoDiv = document.createElement('div');
            productoDiv.className = 'producto';
            productoDiv.innerHTML = `
                <input type="text" name="productos[]" placeholder="Nombre del producto" required>
                <input type="number" name="cantidades[]" placeholder="Cantidad" required>
                <input type="number" name="precios[]" placeholder="Precio unitario" required>
            `;
            container.appendChild(productoDiv);
        });
    </script>
</body>
</html>
