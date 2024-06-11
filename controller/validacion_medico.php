<?php
session_start();
require_once("../db/connection.php");
$db = new Database();
$con = $db->conectar();

// Verificar si se ha enviado el formulario
if(isset($_POST["inicio"])) {
    // Obtener el código enviado desde el formulario
    $codigo_ingresado = $_POST['codigo'];
    // Obtener el código único de la empresa enviado desde el formulario
    $codigo_unico_empresa = $_POST['codigo_unico_empresa'];

    // Realizar cualquier validación adicional según sea necesario
    // ...

    // Validar el código ingresado con el código único de la empresa
    if (strtolower($codigo_ingresado) == strtolower($codigo_unico_empresa)) {
          $documento = $medico['docu_medico'];    
        $nombre = $medico['nombre_comple'];
        $apellido = ''; // No hay apellido en la tabla médicos, así que se deja vacío
        $correo = $medico['correo'];
        $tipo = $medico['id_rol'];
        $nit = $medico['nit'];
        // Redirigir al dashboard del profesional médico
        header("Location: ../model/admins/medico/index.php");
        exit();
    } else {
        // Si la validación inicial falla, redirigir a la página de error
        header("Location: ../error.html");
        exit();
    }
} else {
    // Si no se ha enviado el formulario, redirigir a la página de error
    header("Location: ../error.html");
    exit();
}
?>
