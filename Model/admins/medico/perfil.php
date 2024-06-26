<?php
require_once("../../../db/connection.php"); 
require_once("../../../controller/seguridad.php");

// Establecer conexión a la base de datos
$conexion = new Database();
$con = $conexion->conectar();

// Verificar sesión (session_start() se llama dentro de validarSesion())
validarSesion();

$documento = $_SESSION['docu_medico'] ?? '';
$nombre = $_SESSION['nombre_comple'] ?? '';
$correo = $_SESSION['correo'] ?? '';
$telefono = $_SESSION['telefono'] ?? '';
$rol = $_SESSION['tipo'] ?? '';
$estado = $_SESSION['id_estado'] ?? '';
$especializacion = $_SESSION['id_esp'] ?? '';
$nit = $_SESSION['nit'] ?? '';

// Consultar datos del usuario
$sql = $con->prepare("SELECT * FROM medicos WHERE docu_medico = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.html";</script>';
    exit;
}


// Verificar si el formulario ha sido enviado y el botón de actualización ha sido presionado
if (isset($_POST['update'])) {
    // Recuperar los datos del formulario
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Validar campos vacíos
    if (empty($correo) || empty($telefono)) {
        echo '<script>alert("Existen campos vacíos.");</script>';
    } else {
        try {
            // Consulta SQL para actualizar los datos del usuario
            $consulta = "UPDATE medicos
                         SET telefono = :telefono, correo = :correo
                         WHERE docu_medico = :docu_medico";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':docu_medico', $documento);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Actualizar los datos en la sesión
                $_SESSION['telefono'] = $telefono;
                $_SESSION['correo'] = $correo;


                echo '<script>alert("Actualización exitosa."); window.location.href = "perfil.php";</script>';
            } else {
                throw new Exception("Error al actualizar los datos.");
            }
        } catch (Exception $e) {
            echo '<script>alert("' . $e->getMessage() . '");</script>';
        }
    }
}


?> 



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, AdminWrap lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, AdminWrap lite design, AdminWrap lite dashboard bootstrap 5 dashboard template">
    <meta name="description" content="AdminWrap Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Medico</title>
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/log.png">
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="../medico/css/perfilmedico.css" rel="stylesheet">
    <link href="css/colors/default.css" id="theme" rel="stylesheet">

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
                <div class="logg">
                            <img src="../../../assets/img/logo.png">
                            </div>
                </div>
                <div class="navbar-collapse">
                
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav me-auto">
                    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
        <h3 class="titulo">Bienvenido/a Medico <?php echo $_SESSION['nombre']; ?></h3>
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
                        <li> <a class="waves-effect waves-dark" href="modulomedico.php" aria-expanded="false">
                        <i class="fas fa-briefcase-medical"></i><span class="hide-menu">Módulo Médico</span></a>
                        </li>
                      
                        
                    </ul>
                    </nav>
                <div class="boton">
                <form method="POST" action="../../../index.html">
                    <button class="botones" type="submit" name="btncerrar">Cerrar sesión</button>
                </form>
    </div>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                        <div class="perfil">
                        <h3 class="text-themecolor">Perfil</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                            <li class="breadcrumb-item active">Perfil</li>
                        </ol>
                    
    </div>
                   
                </div>
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="mt-4"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="120px" height="120px" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                                </svg>

                                    <h4 class="card-title mt-2"> <?php echo $_SESSION['nombre'];?></h4>
                                   
                                    <?php  
                                    
                                    $control = $con->prepare("SELECT * fROM roles where id_rol = '$rol'");
                                    // $control -> bindParam(':rol', $rol);
                                    $control -> execute();
                                    $consulta = $control->fetch();
                                    
                                    ?>

                                    <h6 class="subtitulo"><?php echo $consulta['rol'] ;   ?> </h6>
                                    
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
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Documento:</label>
                        <input type="text" value=" <?php echo $_SESSION['documento'];?>" class="form-control form-control-line" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Nombre Completo:</label>
                        <input type="text" placeholder="<?php echo $_SESSION['nombre']; ?>" class="form-control form-control-line" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Correo:</label>
                        <input type="text" name="correo" id="correo" pattern="[0-9a-zA-Z@_.-]{7,60}" title="El correo debe contener un @ y debe contener minimo 7 digitos" value ="<?php echo $correo ;?>" class="form-control form-control-line">
                    </div>
                    <div class="col-md-6">
                        <label>Telefono:</label>
                        <input type="text" name="telefono" id="telefono" pattern="[0-9]{10}" title="El telefono debe contener solo numeros (10 digitos)" value="<?php echo $telefono ; ?>" class="form-control form-control-line">
                    </div>
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

            <footer class="footer"> © 2024 EPS Vitalfarma Todos los derechos reservados. </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function(){
        $('#id_depart').change(function(){
            var id_depart = $(this).val();
            $.ajax({
                type: "POST",
                url: "municipio.php",
                data: {id_depart: id_depart},
                success: function(response){
                    $('#id_municipio').html(response);
                }
            });
        });
    });
    </script>

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