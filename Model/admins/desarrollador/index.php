<?php

   require_once ("../../../db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
//    session_start();
?>

<?php
require_once("../../../controller/seguridad.php");
validarSesion();
?>
<?php
$sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();
echo"conectado";

$documento=$_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
$direccion = $_SESSION['direccion'];
$telefono =$_SESSION['telefono'];
$correo = $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$empresa = $_SESSION[ 'nit'];

$nombre_comple = $nombre .''.$apellido; 

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;


}
?>

<?php
// Conexión a la base de datos


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
    <title>Desarrollador</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/adminwrap-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <!-- Bootstrap Core CSS -->
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/node_modules/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--c3 CSS -->
    <link href="assets/node_modules/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="css/pages/dashboard1.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/default.css" id="theme" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->


<?php 

if(isset($_POST['btncerrar']))
{
    session_destroy();

   
    header('location: ../../../index.html');
}
    
?>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">VitalFarma</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    
                    <a class="navbar-brand" href="index.html">
                   
                    
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <h5 class="logg">Vital<spam class="sombra" >Farma</spam></h5>
                         
                            <!-- Dark Logo icon -->
                
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                            <!-- dark Logo text -->
                            <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" /> -->
                            
                            <!-- Light Logo text -->

                            <!-- <img src="../assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span> -->
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav me-auto">
                     <h1 class="rol">Administrador</h1>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown u-pro">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="#"
                                id="navbarDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span
                                    class="hidden-md-down"><?php echo $nombre_comple ?> &nbsp;</span> </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown"></ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li> <a class="waves-effect waves-dark" href="index.php" aria-expanded="false"><i
                                    class="fa fa-tachometer"></i><span class="hide-menu">Principal</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="perfil.php" aria-expanded="false"><i
                                    class="fa fa-user-circle-o"></i><span class="hide-menu">Perfil</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="usuarios.php" aria-expanded="false"><i
                                    class="fa fa-table"></i><span class="hide-menu">Usuarios </span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="modulomedico.php" aria-expanded="false"><i
                                    class="fa fa-smile-o"></i><span class="hide-menu">modulo medico</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="citas.php" aria-expanded="false"><i
                                    class="fa fa-globe"></i><span class="hide-menu">Citas</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="datosgenerales.php" aria-expanded="false"><i
                                    class="fa fa-bookmark-o"></i><span class="hide-menu">Datos generales</span></a>
                        </li>
                        
                    </ul>
                    <form method="POST">
    <tr>
        <td colspan='1'></td>
    </tr>

    <input type="submit" value="Cerrar sesion" name="btncerrar"  class="btn waves-effect waves-light btn-info hidden-md-down text-white" >
</tr>
</form>
                 
                </nav>
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
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                   
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Sales Chart and browser state-->
                <!-- ============================================================== -->
                

                   <!-- ============================================================== -->
                   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
               
                <div class="card-container">
                    <!-- Carta para Ciudad -->
                    <div class="card">
                        <a href="empresa/index_emp.php">
                            <div class="card_box">
                                <h3>empresas</h3>
                                <p class="card_box__content">Crear empresas</p>
                                <div class="card__date">Haz clic para acceder y gestionar y crear empresas.</div>
                                <div class="card_box__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="15" width="15">
                                        <path fill="#fff" d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Carta para Estados -->
                  
                
            </div>
        </div>
    </div>
</div>

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
            $consulta = "SELECT *
                         FROM usuarios
                        --  JOIN ciudad ON usuarios.id_ciudad = ciudad.id_ciudad
                         JOIN empresas ON usuarios.nit = empresas.nit
                         JOIN estados ON usuarios.id_estado = estados.id_estado
                         JOIN roles ON usuarios.id_rol = roles.id_rol
                        ";  // Condición para filtrar por empresa
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

                $control = $con->prepare("SELECT * FROM roles");
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
                echo "<button type='submit' name='update' class='btn btn-primary btn-sm'>Actualizar</button>";
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
                    document.getElementById("search").addEventListener("keyup", function() {
                        var searchTerm = this.value.toLowerCase();
                        var rows = document.querySelectorAll("#userTable tr");
            
                        rows.forEach(function(row) {
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
            <footer class="footer"> © 2021 Adminwrap by <a href="https://www.wrappixel.com/">wrappixel.com</a> </footer>
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