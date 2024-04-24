<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Nunha Paxina</title>
</head>

<body>
    <form action="ejercicio8.php" method="post">
        <fieldset>
            <legend>IES Chan do Monte</legend>
            <p>Nome Completo: <input type="text" name="nome" maxlength="100" value="<?php if (isset($_POST['nome'])) { echo $_POST['nome']; } ?>"></p>
            <p>DNI: <input type="text" name="dni" value="<?php if (isset($_POST['dni'])) { echo $_POST['dni']; } ?>"></p>
            <p>Contrasinal: <input type="password" name="contrasinal" value="<?php if (isset($_POST['contrasinal'])) { echo $_POST['contrasinal']; } ?>"></p>
        </fieldset>
        <br>
        <fieldset>
            <legend>Asignaturas a cursar</legend>
            DWCS <input type="checkbox" name="asignaturas[]" value="DWCS" <?php if (isset($_POST['asignaturas'])) { if (in_array("DWCS", $_POST['asignaturas'])) { echo "checked"; } }?>>
            DAW <input type="checkbox" name="asignaturas[]" value="DAW" <?php if (isset($_POST['asignaturas'])) { if (in_array("DAW", $_POST['asignaturas'])) { echo "checked"; } } ?>>
            DWCC <input type="checkbox" name="asignaturas[]" value="DWCC" <?php if (isset($_POST['asignaturas'])) { if (in_array("DWCC", $_POST['asignaturas'])) { echo "checked"; } }?>>
        </fieldset>
        <input type="hidden" name="activar" value="1">
        <br>
        <input type="submit" name="enviar" value="Enviar">
    </form>
</body>

<?php
// Comprobaciones más concretas para darle los mensajes de errores correctos al usuario
if (isset($_POST['enviar'])) {
    $bandera = 1;

    if (empty($_POST['nome'])) {
        $bandera = 0;
        echo "<br>* El campo 'nombre' está vacío";
    }

    if (empty($_POST['dni'])) {
        $bandera = 0;
        echo "<br>* El campo 'dni' está vacío";
    } else {
        $dni = $_POST['dni'];
        if (!preg_match('/^(\d{8}[A-Z])$/', $dni)) {
            $bandera = 0;
            echo "<br>* El DNI tiene que seguir el formato 12345678A";
        }
    }

    if (empty($_POST['contrasinal'])) {
        $bandera = 0;
        echo "<br>* El campo 'contrasinal' está vacío";
    }

    if (!isset($_POST['asignaturas'])) {
        $bandera = 0;
        echo "<br>* Tienes que marcar como mínimo una asignatura";
    }

    if ($bandera == 1) {
        echo "<h2>Matrícula IES Chan do Monte</h2>";
        echo "Nome completo: " . $nome . "<br>";
        echo "DNI: " . $dni . "<br>";
        echo "Contrasinal: " . $contrasinal . "<br>";

        echo "Asignaturas escollidas: ";
        foreach ($_POST['asignaturas'] as $a) {
            echo $a . " ";
        }
    } else {
        echo "<br>Revise el formulario para corregir los errores";
    }
}
?>

</html>