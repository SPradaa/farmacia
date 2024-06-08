<?php
require_once("../../db/connection.php"); 
require_once("../../controller/segpacientes.php");

// Establecer conexión a la base de datos
$conexion = new Database();
$con = $conexion->conectar();

// Verificar sesión (session_start() se llama dentro de validarSesion())
validarSesion();

// Obtener datos del usuario de la sesión
$documento = $_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
$direccion = $_SESSION['direccion'];
$telefono = $_SESSION['telefono'];
$correo = $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$empresa = $_SESSION['nit'];
$nombre_comple = $nombre . ' ' . $apellido;

// Consultar datos del usuario
$sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

$sql = $con -> prepare ("SELECT * FROM usuarios, municipios, departamentos
WHERE usuarios.id_municipio = municipios.id_municipio AND municipios.id_depart = departamentos.id_depart");
$sql -> execute();
$usua =$sql -> fetch();

// Verificar si el formulario ha sido enviado y el botón de actualización ha sido presionado
if (isset($_POST['update'])) {
    // Recuperar los datos del formulario
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['id_municipio'];

    // Validar campos vacíos
    if (empty($correo) || empty($telefono) || empty($ciudad) || empty($direccion)) {
        echo '<script>alert("Existen campos vacíos.");</script>';
    } else {
        try {
            // Consulta SQL para actualizar los datos del usuario
            $consulta = "UPDATE usuarios 
                         SET telefono = :telefono, id_municipio = :ciudad, direccion = :direccion, correo = :correo
                         WHERE documento = :documento";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':documento', $documento);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Actualizar los datos en la sesión
                $_SESSION['telefono'] = $telefono;
                $_SESSION['direccion'] = $direccion;
                $_SESSION['correo'] = $correo;

                // Obtener el nombre del municipio y el departamento actualizados
                $consulta_municipio = "SELECT m.municipio, d.depart, d.id_depart
                                       FROM municipios m
                                       INNER JOIN departamentos d ON m.id_depart = d.id_depart
                                       WHERE m.id_municipio = :ciudad";
                $stmt_municipio = $con->prepare($consulta_municipio);
                $stmt_municipio->bindParam(':ciudad', $ciudad);
                $stmt_municipio->execute();
                $resultado = $stmt_municipio->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $_SESSION['id_depart'] = $resultado['id_depart'];
                    $_SESSION['depart'] = $resultado['depart'];
                    $_SESSION['id_municipio'] = $ciudad;
                    $_SESSION['municipio'] = $resultado['municipio'];
                } else {
                    throw new Exception("No se encontraron los datos del municipio y departamento.");
                }

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
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/log.png">
    <!-- Bootstrap Core CSS -->
    <link href="assets/node_modules/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link href="css/perfil.css" rel="stylesheet">
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
                <div class="logg">
                            <img src="../../assets/img/log.farma.png">
                            </div>
                </div>
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
                <form method="POST">
        <button class="botones" type="submit" name="btncerrar">Cerrar sesión</button>
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
                        <div class="perfil">
                        <h3 class="text-themecolor">Perfil</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                            <li class="breadcrumb-item active">Perfil</li>
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
                        <input type="text" placeholder="<?php echo $nombre_comple ;?>" class="form-control form-control-line" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Correo:</label>
                        <input type="text" pattern="[A-Za-z0-9@._-]{7,60}" title="El correo debe contener el @ y debe ser alfanumerico" value ="<?php echo $correo ;?>"
                        class="form-control form-control-line" name="correo" >
                    </div>
                    <div class="col-md-6">
                        <label>Telefono:</label>
                        <input type="text" pattern="[0-9]{10}" title="El telefono debe tener solo numeros (10 digitos)" value="<?php echo $telefono ; ?>"
                        class="form-control form-control-line" name="telefono" >
                    </div>
                </div>
                <div class="form-group row">
    <div class="col-md-6">
        <label>Departamento:</label>
        <select id="id_depart" name="id_depart" class="form-control form-control-line">
            <option value="<?php echo $_SESSION['id_depart']?>"><?php echo $_SESSION['depart']?></option>
            <?php
                $control = $con->prepare("SELECT * FROM departamentos");
                $control->execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=" . $fila['id_depart'] . ">" . $fila['depart'] . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="col-md-6">
        <label>Municipio:</label>
        <select id="id_municipio" name="id_municipio" class="form-control form-control-line">
            <option value="<?php echo $_SESSION['id_municipio']?>"><?php echo $_SESSION['municipio']?></option>
        </select>
    </div>
</div>



                    <div class="col-md-6">
                        <label>Dirección:</label>
                        <input type="text" pattern="[A-Za-z0-9._-´# ]{7,40}" title="La dirección debe tener minimo 5 caracteres" value="<?php echo $direccion ?>"
                        class="form-control form-control-line" name="direccion">
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

            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
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