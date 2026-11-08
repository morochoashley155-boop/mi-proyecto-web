<?php
// Incluir el archivo de configuración con las credenciales reales
include('../config.php');

// Cabecera para indicar que se descarga un archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=datos.csv');

// Abrir la salida de texto para escribir el CSV
$output = fopen('php://output', 'w');

// Poner los encabezados exactos de la tabla ventas
fputcsv($output, array('fecha', 'producto', 'categoría', 'vendedor', 'clientes', 'cantidad', 'precio', 'total'));

// Conexión real a tu base de datos de AwardSpace
$conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conexion) {
    $query = "SELECT fecha, producto, categoria, vendedor, cliente, cantidad, precio, total FROM ventas";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
    }
    mysqli_close($conexion);
}
fclose($output);
?>
