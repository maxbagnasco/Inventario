<?php
// Conexión DB (reemplazo de valores)
$servername = "nombre_servidor";
$username = "nombre_usuario";
$password = "contraseña";
$dbname = "nombre_base_de_datos";

//Ejecución consultas y obtención de resultados 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Consultar SQL = total de ventas por cada producto
$sql = "SELECT p.nombre AS producto, SUM(f.monto_total) AS total_ventas
        FROM productos p
        INNER JOIN facturas_detalle fd ON p.id = fd.producto_id
        INNER JOIN facturas f ON fd.factura_id = f.id
        GROUP BY p.id";

$result = $conn->query($sql);

// Expone resultados en tabla
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Producto</th>
                <th>Total Ventas</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["producto"] . "</td>
                <td>" . $row["total_ventas"] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

$conn->close();
?>
