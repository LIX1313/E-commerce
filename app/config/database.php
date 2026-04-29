<?php
$servidor = "localhost";
$usuario = "root";
$clave = "1234";
$base_datos = "practica1_backend";
$puerto = 3306;

$conn = new mysqli($servidor, $usuario, $clave, $base_datos, $puerto);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
