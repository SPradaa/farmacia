<?php

   require_once ("../../db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
//    session_start();
?>

<?php
require_once("../../controller/segpacientes.php");
validarSesion();

?>

<?php
require_once("../../controller/segpacientes.php");
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
$correo= $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$empresa = $_SESSION[ 'nit'];

$nombre_comple = $nombre .''.$apellido; 

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;


}

// Variables para el usuario



    // $_SESSION['documento'] = $fila['documento'];
    // $_SESSION['nombre'] = $fila['nombre'];
    // $_SESSION[ 'apellido'] = $fila['apellido'];
    // $_SESSION[ 'direccion'] = $fila['direccion'];
    // $_SESSION['telefono'] = $fila['telefono'];
    // $_SESSION['correo'] = $fila['correo'];
    // $_SESSION['password'] = $fila['password'];
    // $_SESSION['tipo'] = $fila['id_rol'];
    // $_SESSION['nit'] = $fila['nit'];

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
    <title>Citas</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/adminwrap-lite/" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/log.png">
    <!-- Bootstrap Core CSS -->
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/stylee.css" rel="stylesheet">
    <!-- page css -->
    <link href="css/pages/google-vector-map.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/default.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header card-no-border fix-sidebar">
    
<div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">VitalFarma</p>
        </div>
    </div>
    
    <div id="main-wrapper">
        
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <div class="navbar-header">
                <div class="logg">
                            <img src="../../assets/img/logo.png">
                            </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav me-auto">
                    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="titulo">Bienvenido/a Paciente <?php echo $nombre;?></h3>
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
                        <i class="fa fa-user-circle-o"></i><span class="hide-menu">Perfil</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="citas.php" aria-expanded="false">
                        <i <a class="fas fa-archive"></i><span class="hide-menu">Citas</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="autorizaciones.php" aria-expanded="false">
                        <i class="fas fa-clipboard-check"></i><span class="hide-menu">Autorizaciones</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="historial.php" aria-expanded="false">
                        <i class="fas fa-users"></i><span class="hide-menu">Historial</span></a>
                        </li>
                             
                    </ul>
                
                </nav>
                <div class="boton">
                <form method="POST" action="../../index.html">
                    <button class="btn" type="submit" name="btncerrar">Cerrar sesión</button>
                </form>
                
                </div>
                
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
                    <div class="medicamentos">
                        <h3 class="text-themecolor">Citas</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Principal</a></li>
                            <li class="breadcrumb-item active">Modulo Citas</li>
                        </ol>
                    </div>
                </div>
              
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
                        <a href="citas/agendarcitas.php">
                            <div class="card_box">
                                <h3 class="car_box__title">Agendar Citas</h3>
                                <p class="card_box__content">Administra tus citas en este módulo.</p>
                                <div class="card__date">Haz clic para acceder y programar nuevas citas </div>
                                <div class="card_box__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="15" width="15">
                                        <path fill="#fff" d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>

                    
                    <div class="card">
                        <a href="citas/cancelarcitas.php">
                            <div class="card_box">
                                <h3 class="car_box__title">Cancelar Citas</h3>
                                <p class="card_box__content">Administra y cancela tus citas en este módulo.</p>
                                <div class="card__date">Haz clic para acceder y cancelar citas </div>
                                <div class="card_box__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="15" width="15">
                                        <path fill="#fff" d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z"></path>
                                    </svg>
                                </div>
                            </div>
    
                        </a>
                    </div>

                    <!-- Carta para el módulo de citas agendadas -->
                    <div class="card">
                        <a href="citas/citasagendadas.php">
                            <div class="card_box">
                                <h3 class="car_box__title">Citas Agendadas</h3>
                                <p class="card_box__content">Visualiza tus citas agendadas en este módulo.</p>
                                <div class="card__date">Haz clic para acceder y ver tus citas programadas.</div>
                                <div class="card_box__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="15" width="15">
                                        <path fill="#fff" d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z"></path>
                                    </svg>
                                </div>
    
                            </div>
                        </a>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>

               
      

            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © 2024 EPS Vitalfarma Todos los derechos reservados.  </footer>
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
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/node_modules/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <!-- google maps api -->
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCUBL-6KdclGJ2a_UpmB2LXvq7VOcPT7K4&sensor=true"></script>
    <script src="assets/node_modules/gmaps/gmaps.min.js"></script>
    <script src="assets/node_modules/gmaps/jquery.gmaps.js"></script>
</body>

</html>