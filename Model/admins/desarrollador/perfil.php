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
// Verificar si el formulario ha sido enviado y el botón de actualización ha sido presionado
if (isset($_POST['update'])) {
    // Recuperar los datos del formulario
     $correo= $_POST['correo']; 
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    
    // Consulta SQL para actualizar los datos del usuario
    $consulta = "UPDATE usuarios 
                 SET telefono = '$telefono', direccion = '$direccion', correo = '$correo'
                 WHERE documento = $documento";
    
    // Ejecutar la consulta
    if ($con->query($consulta) === TRUE) {
        echo "Los datos se actualizaron correctamente.";
    } else {
        echo "Error al actualizar los datos: " ;
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
    <title>Perfil</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/adminwrap-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <!-- Bootstrap Core CSS -->
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
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
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        
                        <!-- Logo icon -->     <h5 class="logg">Vital<spam class="sombra" >Farma</spam></h5>
              
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
                     <h1 class="rol">Desarrollador</h1>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown u-pro">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href=""
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span
                                    class="hidden-md-down"><?php echo $nombre_comple ;?> &nbsp;</span> </a>
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
                       
                        
                    </ul>
                  
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
                        <h3 class="text-themecolor">Perfil</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                   
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="mt-4"><svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 36 36"><path fill="#000000" d="M14.68 14.81a6.76 6.76 0 1 1 6.76-6.75a6.77 6.77 0 0 1-6.76 6.75m0-11.51a4.76 4.76 0 1 0 4.76 4.76a4.76 4.76 0 0 0-4.76-4.76" class="clr-i-outline clr-i-outline-path-1"/><path fill="#000000" d="M16.42 31.68A2.14 2.14 0 0 1 15.8 30H4v-5.78a14.81 14.81 0 0 1 11.09-4.68h.72a2.2 2.2 0 0 1 .62-1.85l.12-.11c-.47 0-1-.06-1.46-.06A16.47 16.47 0 0 0 2.2 23.26a1 1 0 0 0-.2.6V30a2 2 0 0 0 2 2h12.7Z" class="clr-i-outline clr-i-outline-path-2"/><path fill="#000000" d="M26.87 16.29a.37.37 0 0 1 .15 0a.42.42 0 0 0-.15 0" class="clr-i-outline clr-i-outline-path-3"/><path fill="#000000" d="m33.68 23.32l-2-.61a7.21 7.21 0 0 0-.58-1.41l1-1.86A.38.38 0 0 0 32 19l-1.45-1.45a.36.36 0 0 0-.44-.07l-1.84 1a7.15 7.15 0 0 0-1.43-.61l-.61-2a.36.36 0 0 0-.36-.24h-2.05a.36.36 0 0 0-.35.26l-.61 2a7 7 0 0 0-1.44.6l-1.82-1a.35.35 0 0 0-.43.07L17.69 19a.38.38 0 0 0-.06.44l1 1.82a6.77 6.77 0 0 0-.63 1.43l-2 .6a.36.36 0 0 0-.26.35v2.05A.35.35 0 0 0 16 26l2 .61a7 7 0 0 0 .6 1.41l-1 1.91a.36.36 0 0 0 .06.43l1.45 1.45a.38.38 0 0 0 .44.07l1.87-1a7.09 7.09 0 0 0 1.4.57l.6 2a.38.38 0 0 0 .35.26h2.05a.37.37 0 0 0 .35-.26l.61-2.05a6.92 6.92 0 0 0 1.38-.57l1.89 1a.36.36 0 0 0 .43-.07L32 30.4a.35.35 0 0 0 0-.4l-1-1.88a7 7 0 0 0 .58-1.39l2-.61a.36.36 0 0 0 .26-.35v-2.1a.36.36 0 0 0-.16-.35M24.85 28a3.34 3.34 0 1 1 3.33-3.33A3.34 3.34 0 0 1 24.85 28" class="clr-i-outline clr-i-outline-path-4"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                    <h4 class="card-title mt-2"> <?php echo $_SESSION['nombre'];?></h4>

                                    <?php  
                                    
                                    $control = $con->prepare("SELECT * fROM roles where id_rol = '$rol'");
                                    // $control -> bindParam(':rol', $rol);
                                    $control -> execute();
                                    $consulta = $control->fetch();
                                    
                                    ?>

                                    <h6 class="card-subtitle"><?php echo $consulta['rol'] ;   ?> </h6>
                                    <div class="row text-center justify-content-md-center">
                                        <div class="col-4"></div>
                                        <div class="col-4"><a href="javascript:void(0)" class="link"><i
                                                    class="fa fa-camera"></i>
                                                <font class="font-medium">54</font>
                                            </a></div>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Tab panes -->
                            <div class="card-body">
                                <form class="form-horizontal form-material mx-2" method="POST">
                                    <div class="form-group">
                                        <label class="col-md-12">Nombre Completo</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder=" "
                                                class="form-control form-control-line" value="<?php echo $nombre_comple ;?>"  disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Correo</label>
                                        <div class="col-md-12">
                                            <input type="text" value ="<?php echo $correo;?>"
                                                class="form-control form-control-line" name="correo" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-12">Documento</label>
                                        <div class="col-md-12">
                                            <input type="text" value=" <?php echo $_SESSION['documento'];?>"
                                                class="form-control form-control-line" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Telefono</label>
                                        <div class="col-md-12">
                                            <input type="text" value="<?php echo $telefono ; ?>"
                                                class="form-control form-control-line" name="telefono" >
                                        </div>
                                    </div>
                                  
                                    <div class="form-group">
                                        <label class="col-md-12">Direccion</label>
                                        <div class="col-md-12">
                                            <input type="text" value="<?php echo $direccion ?>"
                                                class="form-control form-control-line" name="direccion">
                                        </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="submit" name="update" class="btn btn-success" value="Actualizar Datos"">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
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
</body>

</html>