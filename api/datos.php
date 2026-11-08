<?php
// Incluir el archivo de configuración con las credenciales reales
include('../config.php');

// Recibir los parámetros obligatorios desde la URL
$token_recibido = $_GET['token'] ?? '';
$recurso_recibido = $_GET['recurso'] ?? '';
$formato_recibido = $_GET['formato'] ?? '';

// 1. Validar que el token de la URL coincida con el de config.php
if ($token_recibido !== API_TOKEN) {
    header("HTTP/1.1 401 Unauthorized");
    die("Error: Token de seguridad incorrecto.");
}

// 2. Validar que estén solicitando el recurso y formato correctos según la guía
if ($recurso_recibido !== 'ventas' || $formato_recibido !== 'csv') {
    header("HTTP/1.1 400 Bad Request");
    die("Error: Recurso o formato no válido.");
}

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
    $query = "SELECT fecha, producto, categoria, vendedor, cliente, cantidad, precio, (cantidad * precio) AS total FROM ventas";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
