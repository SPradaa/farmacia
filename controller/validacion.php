<?php
// Incluir el archivo de conexión a la base de datos
require_once(" ../../../db/connection.php");

// Crear una nueva instancia de la clase Database
$conexion = new Database();

// Establecer una conexión a la base de datos
$con = $conexion->conectar();

// Iniciar la sesión
session_start();

// Verificar si se ha enviado el formulario
if(isset($_POST["inicio"])) {
    // Obtener el código del formulario
    $codigo = $_POST['codigo'];

    // Realizar cualquier validación adicional según sea necesario
    // ...

    // Consultar usuarios
    $consultaUsuario = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $consultaUsuario->bindParam(':documento', $_SESSION['documento']);
    $consultaUsuario->execute();
    $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra el usuario en la tabla usuarios, buscar en la tabla medicos
    if (!$usuario) {
        $consultaMedico = $con->prepare("SELECT * FROM medicos WHERE docu_medico = :documento");
        $consultaMedico->bindParam(':documento', $_SESSION['documento']);
        $consultaMedico->execute();
        $medico = $consultaMedico->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra el médico, asignar sus datos a las variables
        if ($medico) {
            $documento = $medico['docu_medico'];
            $nombre = $medico['nombre_comple'];
            $apellido = ''; // No hay apellido en la tabla medicos, así que se deja vacío
            $correo = $medico['correo'];
            $tipo = $medico['id_rol'];
            $nit = $medico['nit'];
        } else {
            // Si no se encuentra el médico, destruir la sesión y redirigir a la página de error
            header("Location: ../error.html");
            session_destroy();
            exit();
        }
    } else {
        // Si se encuentra el usuario en la tabla usuarios, asignar sus datos a las variables
        $documento = $usuario['documento'];
        $nombre = $usuario['nombre'];
        $apellido = $usuario['apellido'];
        $correo = $usuario['correo'];
        $tipo = $usuario['id_rol'];
        $nit = $usuario['nit'];
    }

    // Consultar datos de la empresa asociada al NIT
    $consultaEmpresa = $con->prepare("SELECT * FROM empresas WHERE nit = :nit");
    $consultaEmpresa->bindParam(':nit', $nit);
    $consultaEmpresa->execute();
    $empresa = $consultaEmpresa->fetch(PDO::FETCH_ASSOC);

    // Almacenar los datos de la empresa en variables
    $nitVerificacion = $empresa['nit'];
    $nombreEmpresa = $empresa['empresa'];
    $idLlave = $empresa['id_llave'];
    $codigoUnico = $empresa['codigo_unico'];

    // Validar el código ingresado con el código único de la empresa
    if ($codigo == $codigoUnico) {
        // Redirigir según el tipo de usuario
        if($tipo == 1) {
            // Redirigir al dashboard del desarrollador
            header("Location: ../model/admins/desarrollador/index.php");
            exit();
        } elseif($tipo == 2 ) {
            // Redirigir al dashboard del administrador
            header("Location: ../model/admins/administrador/index.php");
            exit();
        } elseif($tipo == 3) {
            // Redirigir al dashboard del profesional médico
            header("Location: ../medico/indexmed.php");
            exit();
        } elseif($tipo == 4) {
            // Redirigir al dashboard del farmacéutico
            header("Location: ../model/admins/farmaceuta/index.php");
            exit();
        }
    } else {
        // Si la validación inicial falla, destruir la sesión y redirigir a la página de error
        header("Location: ../error.html");
        session_destroy();
        exit();
    }
} else {
    // Si no se ha enviado el formulario, destruir la sesión y redirigir a la página de error
    header("Location: ../error.html");
    session_destroy();
    exit();
}
?>