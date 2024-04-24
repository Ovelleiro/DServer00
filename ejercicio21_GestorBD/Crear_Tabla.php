<?php
session_start();
include("Conexion_BD.php");
if (!isset($_SESSION['bd'])) {
    header('location: seleccionar_bd.php');
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Crear Tabla</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <style>
    form, p {
        margin: 30px;
    }

    form button, form label {
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="inicio.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="crear_bd.php">Crear BD</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="seleccionar_bd.php">Seleccionar BD</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button"
                                data-bs-toggle="dropdown">CRUD</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="crear_tabla.php">Crear</a></li>
                                <li><a class="dropdown-item" href="visualizar_tabla.php">Visualizar</a></li>
                                <li><a class="dropdown-item" href="modificar_tabla.php">Modificar</a></li>
                                <li><a class="dropdown-item" href="eliminar_tabla.php">Eliminar</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <label style="float:right;color:white"><?php if (isset($_SESSION['bd'])) { echo "Conectado a: ".$_SESSION['bd']; } ?></label>
            </div>
        </nav>
    </header>
    <main>
        <form action="crear_tabla.php" method="post">
            <div class="mb-3">
                <label for="nombreTabla" class="form-label">Nombre de la Tabla:</label>
                <input type="text" class="form-control" name="nombreTabla" id="nombreTabla" placeholder="Introduce aquí el nombre de la tabla" />

                <label for="numeroCampos" class="form-label">Número de campos:</label>
                <input type="number" class="form-control" name="numeroCampos" id="numeroCampos" placeholder="Introduce aquí el número de campos" />

                <button type="submit" name="enviar" class="btn btn-primary">Envíar</button>
            </div>
        </form>
    </main>

    <?php if (isset($_POST['enviar'])) { ?>
    <form action="crear_tabla.php" method="post">
        <div>
    <?php
    if (isset($_POST['numeroCampos'])) {
        for ($i = 0; $i < $_POST['numeroCampos']; $i++) {
            $nombreCampo = "nombreCampo".$i;
            $tipoCampo = "tipoCampo".$i;
            $clavePrimaria = "clavePrimaria".$i;
            $nulos = "nulos".$i;

            echo "<input style='width:290px;' type='text' name='$nombreCampo' id='$nombreCampo' placeholder='Introduce aquí el nombre del campo ".($i + 1)."' />";

            echo "<label style='margin-left:10px;margin-right:10px;' for='$tipoCampo' class='form-label'>Tipo del campo ".($i + 1).":</label>";
            echo "<select name='$tipoCampo' id='$tipoCampo'>";
                echo "<option>Entero</option>";
                echo "<option>Cadena</option>";
                echo "<option>Booleano</option>";
            echo "</select>";

            echo "<label style='margin-left:10px;margin-right:10px;' for='$clavePrimaria' class='form-label'>Clave Primaria </label>";
            echo "<input type='radio' name='clavePrimaria' id='$clavePrimaria' value='".$i."' required>";

            echo "<label style='margin-left:10px;margin-right:10px;' for='$nulos' class='form-label'>Acepta Nulos </label>";
            echo "<input type='checkbox' name='$nulos' id='$nulos'>";

            $_SESSION['nombreTabla'] = $_POST['nombreTabla'];
            $_SESSION['numeroCampos'] = $_POST['numeroCampos'];

            echo "<br>";
        }
        echo "<button type='submit' name='crear' class='btn btn-primary'>Crear</button>";
    }
        echo "</div>";
    echo "</form>";
    }
    ?>

    <?php
    if (isset($_POST['crear'])) {
        if (isset($_SESSION['numeroCampos']) && isset($_SESSION['nombreTabla'])) {
            $sql = 'CREATE TABLE '.$_SESSION['nombreTabla'].' (';

            for ($i = 0; $i < $_SESSION['numeroCampos']; $i++) {
                // Nombre del campo
                $sql .= $_POST["nombreCampo".$i];
                
                // Tipo del campo
                switch ($_POST["tipoCampo".$i]) {
                    case "Entero":
                        $sql .= " int ";
                        break;
                    case "Cadena":
                        $sql .= " varchar(255) ";
                        break;
                    case "Booleano":
                        $sql .= " bit ";
                        break;
                }

                // Clave primaria?
                if ($_POST['clavePrimaria'] == $i) {
                    $sql .= "PRIMARY KEY ";
                }

                // Acepta nulos?
                if (isset($_POST["nulos".$i])) {
                    $sql .= "NULL ";
                } else {
                    $sql .= "NOT NULL";
                }

                // Control de comas
                if (($i + 1) < $_SESSION['numeroCampos']) {
                    $sql .= ", ";
                } else {
                    $sql .= ");";
                }
            }
        }

        $resultado = $conexion -> query($sql, MYSQLI_USE_RESULT);
        if ($resultado) {
            echo "<p>Se ha creado la tabla ".$_SESSION['nombreTabla']." correctamente</p>";
        } else {
            echo "<p>Ha habido un error al crear la tabla ".$_SESSION['nombreTabla']."</p>";
        }

        /* Iba a usar esta opción para revisar que hubiese 1 clave primaria, pero es mejor usar radios
        if (isset($_SESSION['numeroCampos'])) {
            $numClavesPrimarias = 0;
            for ($i = 0; $i < $_SESSION['numeroCampos']; $i++) {
                // Contar claves primarias, si es diferente de 1 no vale
            }
        } */
    }
    ?>

    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>