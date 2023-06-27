<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroFactura = filter_input(INPUT_POST, 'numero_factura', FILTER_SANITIZE_STRING);
    $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
    $cliente = filter_input(INPUT_POST, 'cliente', FILTER_SANITIZE_STRING);
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];

    $errors = array();

    if (empty($numeroFactura)) {
        $errors[] = "El número de factura es obligatorio.";
    } elseif (!ctype_alnum($numeroFactura)) {
        $errors[] = "El número de factura solo puede contener letras y números.";
    } elseif (strlen($numeroFactura) > 20) {
        $errors[] = "El número de factura no puede exceder los 20 caracteres.";
    }

    if (empty($fecha)) {
        $errors[] = "La fecha es obligatoria.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        $errors[] = "La fecha debe tener el formato YYYY-MM-DD.";
    } elseif (strtotime($fecha) > strtotime('today')) {
        $errors[] = "La fecha no puede ser posterior a la fecha actual.";
    }

    if (empty($cliente)) {
        $errors[] = "El cliente es obligatorio.";
    } elseif (strlen($cliente) > 50) {
        $errors[] = "El nombre del cliente no puede exceder los 50 caracteres.";
    }

    if (count($productos) == 0) {
        $errors[] = "Debe ingresar al menos un producto.";
    } else {
        foreach ($productos as $index => $producto) {
            if (empty($producto)) {
                $errors[] = "El nombre del producto es obligatorio en el ítem " . ($index + 1) . ".";
            }
            if ($cantidades[$index] <= 0) {
                $errors[] = "La cantidad del producto debe ser mayor a cero en el ítem " . ($index + 1) . ".";
            }
            if ($precios[$index] <= 0) {
                $errors[] = "El precio unitario del producto debe ser mayor a cero en el ítem " . ($index + 1) . ".";
            }
        }
    }

    if (empty($errors)) {

        $servername = "nombre_servidor";
        $username = "nombre_usuario";
        $password = "contraseña";
        $dbname = "nombre_base_de_datos";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error en la conexión a la base de datos: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO facturas (numero_factura, fecha, cliente, monto_total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $numeroFactura, $fecha, $cliente, $montoTotal);

        $montoTotal = 0;
        for ($i = 0; $i < count($productos); $i++) {
            $cantidad = $cantidades[$i];
            $precio = $precios[$i];
            $subtotal = $cantidad * $precio;
            $montoTotal += $subtotal;
        }

        if ($stmt->execute()) {
            echo "La factura se ha creado exitosamente.";
        } else {
            echo "Error al crear la factura: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {

        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>

<!-- MODIFICAR VERSION ADAPTADA AL HTML -->

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
