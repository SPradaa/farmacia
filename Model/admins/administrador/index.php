<?php

require_once ("../../../db/connection.php");
$db = new Database();
$con = $db->conectar();
//    session_start();




?>

<?php
require_once ("../../../controller/seguridad.php");
validarSesion();


?>


<?php
$sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();

$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
$direccion = $_SESSION['direccion'];
$telefono = $_SESSION['telefono'];
$correo = $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$nit = $_SESSION['nit'];

$nombre_comple = $nombre . '' . $apellido;

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;


}
?>

<?php

if (isset($_POST['update'])) { // Comprueba si se ha enviado el formulario
    $documento = $_POST['documento']; // Campo oculto para identificar al usuario
    $id_rol = $_POST['id_rol']; // Rol a actualizar
    $id_estado = $_POST['id_estado']; // Estado a actualizar

    // Verificación de datos requeridos
    if (empty($documento) || empty($id_rol) || empty($id_estado)) {
        echo '<script>alert("EXISTEN DATOS VACÍOS");</script>';
        echo '<script>window.location="index.php"</script>';
    } else {
        // Consulta para actualizar el rol y el estado
        $estupdate = $con->prepare("UPDATE usuarios 
                                    SET id_rol = :id_rol, id_estado = :id_estado 
                                    WHERE documento = :documento");

        // Asignar valores a los parámetros
        $estupdate->bindParam(':documento', $documento, PDO::PARAM_INT);
        $estupdate->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
        $estupdate->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

        // Ejecutar la actualización
        if ($estupdate->execute()) {
            echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';

            // Obtener el correo electrónico del usuario actualizado
            $stmt = $con->prepare("SELECT correo FROM usuarios WHERE documento = :documento");
            $stmt->bindParam(':documento', $documento, PDO::PARAM_INT);
            $stmt->execute();
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            $correo = $fila["correo"];

            $paracorreo = "$correo";
            $titulo ="activacion de usuario";
            $msj = "Bienvenido a nuestra plataforma, usted ha sido activado exitosamente";
            
            $tucorreo="From:colaya741@gmail.com";
            if(mail($paracorreo, $titulo, $msj, $tucorreo))
            {
              echo '<script> alert ("Su codigo ha sido enviado al correo anteriormente digitado");</script>';
            //   echo '<script>window.location="code.php"</script>';
            }
            else{
                echo "Error";
            }

            echo '<script>window.location="index.php"</script>';
        } else {
            echo '<script> alert("ERROR AL ACTUALIZAR");</script>';
            echo '<script>window.location="index.php"</script>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, AdminWrap lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, AdminWrap lite design, AdminWrap lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="AdminWrap Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Administrador</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/adminwrap-lite/" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Favicon icon -->
    <link href="../../../assets/img/log.png" rel="icon">
    <link href="../../../assets/img/log.png" rel="apple-touch-icon">
    <!-- Bootstrap Core CSS -->
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/node_modules/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--c3 CSS -->
    <link href="assets/node_modules/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../desarrollador/css/styles.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="css/pages/dashboard1.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/default.css" id="theme" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


    <?php

    if (isset($_POST['btncerrar'])) {
        session_destroy();


        header('location: ../../../index.html');
    }

    ?>
</head>
<style>
    .conte{
    background: white;
    font-weight: 600;
}
h2{
    font-weight: 600;
    text-align: center;
    margin-top: -20px;
}
</style>

<body class="fix-header fix-sidebar card-no-border">
<div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">VitalFarma</p>
        </div>
    </div>
    
    <div id="main-wrapper">

        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                <div class="logg">
                            <img src="../../../assets/img/logo.png">
                            </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav me-auto">
                    <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                            <h3 class="titulo">Bienvenido/a Administrador <?php echo $nombre;?></h3>
                        </div>
                    </div>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item hidden-xs-down search-box"> 
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search & enter"> <a
                                    class="srh-btn"></a> </form>
                        </li>
                    </ul>
                    
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li> <a class="waves-effect waves-dark" href="index.php" aria-expanded="false">
                        <i class="fas fa-heart"></i><span class="hide-menu">Principal</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="perfil.php" aria-expanded="false">
                        <i class="fa fa-user-circle-o"></i><span class="hide-menu" id="perf">Perfil</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="usuarios.php" aria-expanded="false">
                        <i class="fas fa-users"></i><span class="hide-menu">Usuarios</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="modulomedico.php" aria-expanded="false">
                        <i class="fas fa-briefcase-medical"></i><span class="hide-menu">Módulo Médico</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="datosgenerales.php" aria-expanded="false">
                        <i class="fas fa-map-marked-alt"></i><span class="hide-menu">Datos generales</span></a>
                        </li>
                        
                    </ul>
                    </ul>
                  
                  </nav>
                  <div class="boton">
                  <form method="POST" action="../../../index.html">
                      <button class="btn" type="submit" name="btncerrar">Cerrar sesión</button>
                  </form>
                </div>
                  <!-- End Sidebar navigation -->
                </div>
              <!-- End Sidebar scroll-->
            </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="conte">
                        <h3 class="text-themecolor">Principal</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">INICIO</a></li>
                            <li class="breadcrumb-item active">Principal</li>
                        </ol>
                    </div>

                </div>
                



                <!-- <div class="container mt-3"> -->
                <h2>Lista de Usuarios</h2>

                <!-- Campo de búsqueda -->
                <div class="mb-3">
                    <input type="text" id="search" class="form-control" placeholder="Buscar por documento...">
                </div>

                <!-- Div con scroll -->
                <div class="scrollable-div">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Documento</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>EPS</th>
                                <th>tipo de usuario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="userTable">
                            <!-- Código PHP para cargar las filas -->
                            <?php
                            $empresa = $_SESSION['nit'];
                            // Asegúrate de tener una conexión de base de datos válida en $con
                            $consulta = "SELECT * FROM usuarios
                        --  JOIN municipios ON usuarios.id_municipio = municipio.id_municipio
                         JOIN empresas ON usuarios.nit = empresas.nit
                         JOIN estados ON usuarios.id_estado = estados.id_estado
                         JOIN roles ON usuarios.id_rol = roles.id_rol
                         WHERE usuarios.nit = '$nit'
                         ORDER BY CASE WHEN usuarios.id_estado = 4 THEN 0 ELSE 1 END";  // Condición para filtrar por empresa
                            $resultado = $con->query($consulta);

                            while ($fila = $resultado->fetch()) {
                                echo "<tr>";
                                echo "<td>" . $fila["documento"] . "</td>";
                                echo "<td>" . $fila["nombre"] . "</td>";
                                echo "<td>" . $fila["correo"] . "</td>";
                                echo "<td>" . $fila["empresa"] . "</td>";
                                // Campo para la selección del rol
                                echo "<td>";
                                echo "<form method='POST' >";
                                echo "<input type='hidden' name='documento' value='" . $fila['documento'] . "'>";
                                echo "<select name='id_rol'>";
                                echo "<option value='" . $fila['id_rol'] . "'>" . $fila['rol'] . "</option>";

                                $control = $con->prepare("SELECT * FROM roles WHERE id_rol IN (4, 5)");
                                $control->execute();

                                while ($rol = $control->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $rol['id_rol'] . "'>" . $rol['rol'] . "</option>";
                                }

                                echo "</select>";
                                echo "</td>";

                                // Campo de selección para el estado
                                echo "<td>";
                                echo "<select name='id_estado'>";
                                echo "<option value='" . $fila['id_estado'] . "'>" . $fila['estado'] . "</option>";

                                $control = $con->prepare("SELECT * FROM estados WHERE id_estado in (3,4)");
                                $control->execute();

                                while ($estado = $control->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $estado['id_estado'] . "'>" . $estado['estado'] . "</option>";
                                }

                                echo "</select>";
                                echo "</td>";

                                // Botón para enviar el formulario de actualización
                                echo "<td class='text-center'>";
                                echo "<button type='submit' name='update' class='actualizar'>Actualizar</button>";
                                echo "</form>";
                                echo "</td>";

                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>



                <!-- </div> -->

                <!-- Script para el buscador -->
                <script>
                    document.getElementById("search").addEventListener("keyup", function () {
                        var searchTerm = this.value.toLowerCase();
                        var rows = document.querySelectorAll("#userTable tr");

                        rows.forEach(function (row) {
                            var documentColumn = row.querySelector("td:first-child");
                            if (documentColumn) {
                                var documentValue = documentColumn.textContent.toLowerCase();
                                if (documentValue.includes(searchTerm)) {
                                    row.style.display = "";
                                } else {
                                    row.style.display = "none";
                                }
                            }
                        });
                    });
                </script>




                <!-- footer -->
                <!-- ============================================================== -->
                <footer class="footer"> <footer class="footer"> © 2024 EPS Vitalfarma Todos los derechos reservados. </footer>
                </footer>
                <!-- ============================================================== -->
                <!-- End footer -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="assets/node_modules/jquery/jquery.min.js"></script>
        <!-- Bootstrap popper Core JavaScript -->
        <script src="assets/node_modules/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="js/perfect-scrollbar.jquery.min.js"></script>
        <!--Wave Effects -->
        <script src="js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="js/sidebarmenu.js"></script>
        <!--Custom JavaScript -->
        <script src="js/custom.min.js"></script>
        <!-- ============================================================== -->
        <!-- This page plugins -->
        <!-- ============================================================== -->
        <!--morris JavaScript -->
        <script src="assets/node_modules/raphael/raphael-min.js"></script>
        <script src="assets/node_modules/morrisjs/morris.min.js"></script>
        <!--c3 JavaScript -->
        <script src="assets/node_modules/d3/d3.min.js"></script>
        <script src="assets/node_modules/c3-master/c3.min.js"></script>
        <!-- Chart JS -->
        <script src="js/dashboard1.js"></script>
</body>

</html>