<?php
require_once("../../../db/connection.php");
$db = new Database();
$con = $db->conectar();

require_once("../../../controller/seguridad.php");
validarSesion();

// Asegúrate de que la sesión está iniciada y las variables de sesión están definidas
if (!isset($_SESSION)) {
    session_start();
}

// Verificar que 'docu_medico' está establecido en la sesión
if (isset($_SESSION['docu_medico'])) {
    // Obtener la información del médico logueado
    $sql = $con->prepare("SELECT * FROM medicos, t_documento, roles, estados, especializacion, empresas 
                          WHERE medicos.id_doc = t_documento.id_doc 
                          AND medicos.id_rol = roles.id_rol 
                          AND medicos.id_estado = estados.id_estado 
                          AND medicos.id_esp = especializacion.id_esp 
                          AND medicos.nit = empresas.nit 
                          AND docu_medico = :docu_medico");
    $sql->bindParam(':docu_medico', $_SESSION['docu_medico']);
    $sql->execute();
    $fila = $sql->fetch();

    if ($fila) {
        // Asignar los datos a las variables de sesión
        $_SESSION['id_doc'] = $fila['id_doc'];
        $_SESSION['nombre_comple'] = $fila['nombre_comple'];
        $_SESSION['correo'] = $fila['correo'];
        $_SESSION['telefono'] = $fila['telefono'];
        $_SESSION['password'] = $fila['password'];
        $_SESSION['id_rol'] = $fila['id_rol'];
        $_SESSION['id_estado'] = $fila['id_estado'];
        $_SESSION['id_esp'] = $fila['id_esp'];
        $_SESSION['nit'] = $fila['nit'];
    } else {
        echo "No se encontró la información del médico.";
    }
} else {
    echo "El documento del médico no está en la sesión.";
}

// Asignar los datos a variables para uso posterior
$docu_medico = isset($_SESSION['docu_medico']) ? $_SESSION['docu_medico'] : '';
$doc = isset($_SESSION['id_doc']) ? $_SESSION['id_doc'] : '';
$nombre_comple = isset($_SESSION['nombre_comple']) ? $_SESSION['nombre_comple'] : '';
$correo = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';
$telefono = isset($_SESSION['telefono']) ? $_SESSION['telefono'] : '';
$password = isset($_SESSION['password']) ? $_SESSION['password'] : '';
$roles = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : '';
$estado = isset($_SESSION['id_estado']) ? $_SESSION['id_estado'] : '';
$esp = isset($_SESSION['id_esp']) ? $_SESSION['id_esp'] : '';
$nit = isset($_SESSION['nit']) ? $_SESSION['nit'] : '';
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
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark"
                                href="javascript:void(0)"><i class="fa fa-bars"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item hidden-xs-down search-box"> <a
                                class="nav-link hidden-sm-down waves-effect waves-dark" href="javascript:void(0)"><i
                                    class="fa fa-search"></i></a>
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search & enter"> <a
                                    class="srh-btn"><i class="fa fa-times"></i></a></form>
                        </li>
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
                        <h3 class="text-themecolor">Principal</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Casa</a></li>
                            <li class="breadcrumb-item active">Principal</li>
                        </ol>
                    </div>
                   
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Sales Chart and browser state-->
                <!-- ============================================================== -->
                

                <h2>Bienvenido <?php echo $_SESSION['nombre']; ?></h2>

              
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