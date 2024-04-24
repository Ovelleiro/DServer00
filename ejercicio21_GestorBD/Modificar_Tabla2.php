<?php
session_start();
include("Conexion_BD.php");
if (!isset($_SESSION['bd'])) {
    header('location: seleccionar_bd.php');
}
if (!isset($_GET["tabla"])) {
    if (!isset($_SESSION["tabla"])) {
        header('location: modificar_tabla.php');
    }
} else {
    $_SESSION["tabla"] = $_GET['tabla'];
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Modificar Tabla
        <?php if (isset($_SESSION["tabla"])) {
            echo $_SESSION["tabla"];
        } ?>
    </title>
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
                                <li><a class="dropdown-item" href="crear_tabla.php">Crear</a></li>
                                <li><a class="dropdown-item" href="visualizar_tabla.php">Visualizar</a></li>
                                <li><a class="dropdown-item active" href="modificar_tabla.php">Modificar</a></li>
                                <li><a class="dropdown-item" href="eliminar_tabla.php">Eliminar</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <label style="float:right;color:white">
                    <?php if (isset($_SESSION['bd'])) {
                        echo "Conectado a: " . $_SESSION['bd'];
                    } ?>
                </label>
            </div>
        </nav>
    </header>
    <main>
        <form action="modificar_tabla2.php" method="post">
            <?php
            $sql = 'SELECT * FROM ' . $_SESSION["tabla"];
            $resultado = $conexion->query($sql);

            // Forma errónea de conseguir los campos de una tabla
            $infoCampos = $resultado->fetch_fields();

            $contador = 0;
            foreach ($infoCampos as $campo) {
                $nombreCampo = "nombreCampo".$contador;
                $tipoCampo = "tipoCampo".$contador;
                $clavePrimaria = "clavePrimaria".$contador;
                $nulos = "nulos".$contador;

                echo "<input style='width:290px;' type='text' name='$nombreCampo' id='$nombreCampo' value='".$campo->name."' placeholder='Introduce aquí el nombre del campo " . ($contador + 1) . "' />";

                echo "<label style='margin-left:10px;margin-right:10px;' for='$tipoCampo' class='form-label'>Tipo del campo " . ($contador + 1) . ":</label>";
                echo "<select name='$tipoCampo' id='$tipoCampo'>";
                switch ($campo->type) {
                    // Entero
                    case 3:
                        echo "<option selected>Entero</option>";
                        echo "<option>Cadena</option>";
                        echo "<option>Booleano</option>";
                        break;
                    // Booleano
                    case 16:
                        echo "<option>Entero</option>";
                        echo "<option>Cadena</option>";
                        echo "<option selected>Booleano</option>";
                        break;
                    // Cadena
                    case 253:
                        echo "<option>Entero</option>";
                        echo "<option selected>Cadena</option>";
                        echo "<option>Booleano</option>";
                        break;
                    default:
                        echo "<option>Entero</option>";
                        echo "<option>Cadena</option>";
                        echo "<option>Booleano</option>";
                        break;
                }
                echo "</select>";

                echo "<label style='margin-left:10px;margin-right:10px;' for='$nulos' class='form-label'>Acepta Nulos </label>";
                echo "<input type='checkbox' name='$nulos' id='$nulos'>";

                echo "<br>";
                $contador++;
            }
            echo "<button type='submit' name='modificar' class='btn btn-primary'>Modificar</button>";
            ?>
        </form>

        <?php
        if (isset($_POST['modificar'])) {
            // Forma correcta de obtener los campos de la tabla, no olvidar
            $sql2 = 'DESCRIBE '. $_SESSION["tabla"];
            $resultado = $conexion->query($sql2);

            $contador = 0;
            $sqlFinal = "";
            $tipoCampoSQL = "";

            while ($fila = mysqli_fetch_array($resultado)) {
                $nombreCampo = "nombreCampo".$contador;
                $tipoCampo = "tipoCampo".$contador;
                $nulos = "nulos".$contador;

                switch ($_POST[$tipoCampo]) {
                    case "Cadena":
                        $tipoCampoSQL = "varchar(255)";
                        break;
                    case "Entero":
                        $tipoCampoSQL = "int";
                        break;
                    case "Booleano":
                        $tipoCampoSQL = "bit";
                        break;
                }

                $sqlFinal .= 'ALTER TABLE '.$_SESSION["tabla"].' CHANGE '.$fila['Field'].' '.$_POST[$nombreCampo].' '.$tipoCampoSQL.' ';

                if (isset($_POST["nulos".$contador])) {
                    $sqlFinal .= "NULL";
                } else {
                    $sqlFinal .= "NOT NULL";
                }

                $sqlFinal .= "; ";

                $contador++;
            }  

            $resultado2 = $conexion->multi_query($sqlFinal);
            if ($resultado2) {
                echo "<p>La modificación se ha ejecutado correctamente</p>";
            }
        }
        ?>
        
    </main>

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