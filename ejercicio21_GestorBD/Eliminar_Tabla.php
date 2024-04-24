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
    <title>Eliminar Tabla</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <style>
        form, p, table {
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
                                <li><a class="dropdown-item active" href="visualizar_tabla.php">Visualizar</a></li>
                                <li><a class="dropdown-item" href="modificar_tabla.php">Modificar</a></li>
                                <li><a class="dropdown-item" href="eliminar_tabla.php">Eliminar</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <label style="float:right;color:white"><?php if (isset($_SESSION['bd'])) { echo "Conectado a: " . $_SESSION['bd']; } ?>
                </label>
            </div>
        </nav>
    </header>
    <main>
        <table class="table caption-top">
            <caption>Tablas de la BD <?php if (isset($_SESSION['bd'])) { echo $_SESSION['bd']; } ?></caption>
            <thead>
                <tr>
                    <th>Nombre de Tabla</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $resultado = mysqli_query($conexion, "SHOW TABLES");
                while ($tabla = mysqli_fetch_array($resultado)) {
                    echo ("<form action='eliminar_tabla.php' method='post'><tr>");
                    echo ("<td>".$tabla[0]."</td>");
                    echo ("<td><button type='submit' name='borrar' value='".$tabla[0]."' class='btn btn-danger'>X</button></td>");
                    echo ("</tr></form>");
                }
                ?>
            </tbody>
        </table>

        <?php
        if (isset($_POST['borrar'])) {
            $sql = "DROP TABLE ".$_POST['borrar'];

            $resultado = $conexion->query($sql);
            if ($resultado) {
                echo "<p>La tabla ".$_POST['borrar']." se ha borrado correctamente</p>";
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