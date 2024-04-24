<?php
$host = "localhost";
$usuario = "root";
$contrasinal = "";
if (isset($_SESSION['bd'])) {
    $bd = $_SESSION['bd'];
} else {
    $bd = "";
}

$conexion = new mysqli($host, $usuario, $contrasinal, $bd);
$error = $conexion -> connect_errno;
if ($error != null) {
    echo "<p>Error $error conectando a la base de datos: $conexion->connect_error</p>";
    exit();
} 
?>