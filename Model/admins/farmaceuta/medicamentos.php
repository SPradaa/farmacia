<?php
    require_once("../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    // session_start();
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
    <title>Modulo Medico</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/adminwrap-lite/" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets/images/log.png">
    <!-- Bootstrap Core CSS -->
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/medicamento.css" rel="stylesheet">
    <!-- page css -->
    <link href="css/pages/icon-page.css" rel="stylesheet">
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
                            <img src="../../../assets/img/logo.png">
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
            <h3 class="titulo">Bienvenido/a Farmaceuta <?php echo $nombre;?></h3>
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
                        <i class="fa fa-user-circle-o"></i><span class="hide-menu" id="perf">Perfil</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="medicamentos.php" aria-expanded="false">
                        <i class="fas fa-pills"></i><span class="hide-menu" id="medi">Medicamentos </span></a>
                        </li>
                        <!-- <li> <a class="waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="fas fa-archive"></i><span class="hide-menu" id="inve">Inventario</span></a>
                        </li> -->
                        <li> <a class="waves-effect waves-dark" href="#" aria-expanded="false" data-bs-toggle="modal" data-bs-target="#autorizacionesModal">
                        <i class="fas fa-clipboard-check"></i><span class="hide-menu" id="auto">Autorizaciones
                                        
                                    </span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="usuarios.php" aria-expanded="false">
                        <i class="fas fa-users"></i><span class="hide-menu">Usuarios
                                        
                                    </span></a>
                        </li>
                       
                        
                    </ul>
                  
                </nav>
                <div class="boton">
                <form method="POST">
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
                        <div class="medicamentos">
                        <h3 class="text-themecolor">Medicamentos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                            <li class="breadcrumb-item active">Medicamentos</li>
                        </ol>
                    
    </div>
                   
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="card-container">

                                    
                                    <!-- modulo medicamentos  -->
                                    <div class="card">

                                        <a href="medicamentos/index_medicame.php">
                                            <div class="card_box">
                                                <h3 class="car_box__title">Medicamentos
                                                </h3>
                                                <p class="card_box__content">Revisa y consulta la lista de medicamentos en este módulo
                                                </p>
                                                <div class="card__date">
                                                Haz clic para gestionar la información sobre diversos medicamentos.
                                                </div>
                                                <div class="card_box__arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" height="15" width="15">
                                                        <path fill="#fff"
                                                            d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                    </div>


                                    <!-- fin modulo  medicamentos   -->
                                    <!-- inicio de modulo de creacion de medicamentos  -->

                                    <div class="card">

                                        <a href="tipo_medicamento/index_medicam.php">
                                            <div class="card_box">
                                                <h3 class="car_box__title"> Tipo de Medicamentos
                                                </h3>
                                                <p class="card_box__content">Consulta los tipos de medicamentos que hay disponibles.

                                                </p>
                                                <div class="card__date">
                                                Haz clic para gestionar categorías específicas de medicamentos.
                                                </div>
                                                <div class="card_box__arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" height="15" width="15">
                                                        <path fill="#fff"
                                                            d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- fin de la creacion de  medicamentos -->



                                    <!-- modulo laboratorios -->


                                    <div class="card">

                                        <a href="laboratorio/index_lab.php">
                                            <div class="card_box">
                                                <h3 class="car_box__title">Laboratorio de Medicamentos
                                                </h3>
                                                <p class="card_box__content">Accede a la gestión de datos relacionados
                                                    con laboratorios. </p>
                                                <div class="card__date">
                                                    Haz clic para administrar el funcionamiento eficiente de los
                                                    laboratorios.
                                                </div>
                                                <div class="card_box__arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" height="15" width="15">
                                                        <path fill="#fff"
                                                            d="M13.4697 17.9697C13.1768 18.2626 13.1768 18.7374 13.4697 19.0303C13.7626 19.3232 14.2374 19.3232 14.5303 19.0303L20.3232 13.2374C21.0066 12.554 21.0066 11.446 20.3232 10.7626L14.5303 4.96967C14.2374 4.67678 13.7626 4.67678 13.4697 4.96967C13.1768 5.26256 13.1768 5.73744 13.4697 6.03033L18.6893 11.25H4C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75H18.6893L13.4697 17.9697Z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                    </div>



                                    <!-- fin modulo  laboratorios -->


                                </div>



                            </div>
                            <!-- ============================================================== -->
                            <!-- End PAge Content -->
                            <!-- ============================================================== -->
                        </div>
                        <footer class="footer"> © 2024 EPS Vitalfarma Todos los derechos reservados. </footer>
                    </div>
                </div>

                   <!-- Modal de Autorizaciones -->
<div class="modal fade" id="autorizacionesModal" tabindex="-1" aria-labelledby="autorizacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center fw-600 w-100" id="autorizacionesModalLabel">Buscar Autorizaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="autorizacionesForm">
                    <div class="mb-3 text-center">
                        <label for="documentoPaciente" class="form-label text-dark">Documento del Paciente:</label><br>
                        <input type="text" name="documento" class="form-control mx-auto" id="documentoPaciente" pattern="[0-9]{8,10}" placeholder="Digite el documento" title="El documento debe contener solo numeros (8 a 10 digitos)"  style="max-width: 300px;">
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" onclick="buscarAutorizacion()">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    .modal-title {
        font-size: 24px; /* Ajusta el tamaño del título según tu preferencia */
        font-weight: 600;
        margin-left: 20px;
    }

    .modal-content {
        border: 2px solid #343a40; /* Color de borde oscuro */
    }

    .form-label {
        font-weight: 600; /* Peso de la fuente del label */
    }

    .form-control {
        border-color: #343a40; /* Color de borde oscuro para el input */
    }

    .btn-primary {
        background-color: rgb(123, 245, 245); /* Color de fondo azul claro */
        color: black; /* Color de texto blanco */
        border: 1px solid rgb(123, 245, 245);
        margin-top: 20px;
        margin-left: -3px;
        margin-bottom: 20px;
    }
</style>


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

    <!-- Script para manejo de búsqueda de autorizaciones -->
    <script>
        function buscarAutorizacion() {
            var documento = document.getElementById("documentoPaciente").value;

            if (documento === "") {
                alert("El campo está vacío, digite un número de documento.");
                return;
            }

            // Realizar solicitud AJAX para verificar el documento
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "buscar_autorizacion.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "no_existe") {
                        alert("El documento no existe, cámbielo.");
                    } else {
                        window.location.href = "autorizaciones_resultado.php?documento=" + documento;
                    }
                }
            };
            xhr.send("documento=" + documento);
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>