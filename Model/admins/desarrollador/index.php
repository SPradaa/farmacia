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
            
            $tucorreo="From:vitalfarma9@gmail.com";
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
    <title>Desarrollador</title>
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
    <link href="css/styles.css" rel="stylesheet">
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
                            <h3 class="titulo">Bienvenido/a Desarrollador <?php echo $nombre;?></h3>
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
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            <div class="container-fluid">

                <!-- <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                   
                </div> -->
                <div class="row">
    <!-- Módulo de Citas -->
    <div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                

                <div class="card-container">
                    <!-- Carta para el módulo de citas -->
                    <div class="card">
                        <a href="empresa/index_emp.php">
                            <div class="card_box">
                                <h3 class="emp">Empresas</h3>
                                <div class="card__date">Haz clic para acceder, gestionar y crear empresas.</div>
                                <div class="card_box__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="15" width="15">
                                        <path fill="#fff" d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="card">
                        <a href="usuarios/registro.php">
                            <div class="card_box">
                                <h3 class="emp">Crear Administradores</h3>
                                <div class="card__date">Haz clic para acceder, gestionar y administradores.</div>
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

<h2 class="usu">Lista de Usuarios</h2>

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
                <th>Tipo de Usuario</th>
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
                         JOIN empresas ON usuarios.nit = empresas.nit
                         JOIN estados ON usuarios.id_estado = estados.id_estado
                         JOIN roles ON usuarios.id_rol = roles.id_rol
                         WHERE usuarios.id_rol = 2";  // Filtrar por id_rol = 1
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

                $control = $con->prepare("SELECT * FROM roles Where id_rol = 1");
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
            <footer class="footer"> © 2024 EPS Vitalfarma Todos los derechos reservados. </footer>
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