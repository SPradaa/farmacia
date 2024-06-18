<?php
require_once("../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
session_start();

if ($_POST["inicio"]) {
    $documento = $_POST['documento'];
    $password = $_POST['password'];

    $encriptar = htmlentities(addslashes($_POST['password']));

    // Buscar en la tabla usuarios
    $sql_usuarios = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $sql_usuarios->bindParam(':documento', $documento, PDO::PARAM_STR);
    $sql_usuarios->execute();
    $fila = $sql_usuarios->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró en usuarios, buscar en la tabla medicos
    if (!$fila) {
        $sql_medicos = $con->prepare("SELECT * FROM medicos WHERE docu_medico = :documento");
        $sql_medicos->bindParam(':documento', $documento, PDO::PARAM_STR);
        $sql_medicos->execute();
        $fila = $sql_medicos->fetch(PDO::FETCH_ASSOC);

        // Ajuste de nombres de columnas de medicos
        if ($fila) {
            $fila['documento'] = $fila['docu_medico'];
            $fila['nombre'] = $fila['nombre_comple'];
            $fila['apellido'] = ''; // Los médicos no tienen apellido
            $fila['direccion'] = ''; // Los médicos no tienen dirección
            $fila['id_municipio'] = ''; // Los médicos no tienen municipio
            $fila['municipio'] = ''; // Los médicos no tienen municipio
            $fila['id_depart'] = ''; // Los médicos no tienen departamento
            $fila['depart'] = ''; // Los médicos no tienen departamento
            $fila['id_estado'] = $fila['id_estado']; // Ajuste según sea necesario
        }
    }

    // Si se encontraron resultados en alguna tabla
    if ($fila && password_verify($encriptar, $fila['password'])) {
        // Establecer datos de usuario en la sesión
        $_SESSION['documento'] = $fila['documento'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['apellido'] = isset($fila['apellido']) ? $fila['apellido'] : ''; // Si no hay apellido en la tabla medicos
        $_SESSION['direccion'] = isset($fila['direccion']) ? $fila['direccion'] : ''; // Si no hay dirección en la tabla medicos
        $_SESSION['id_municipio'] = $fila['id_municipio'];
        $_SESSION['municipio'] = isset($fila['municipio']) ? $fila['municipio'] : '';
        $_SESSION['id_depart'] = isset($fila['id_depart']) ? $fila['id_depart'] : '';
        $_SESSION['depart'] = isset($fila['depart']) ? $fila['depart'] : '';
        $_SESSION['telefono'] = $fila['telefono'];
        $_SESSION['correo'] = $fila['correo'];
        $_SESSION['password'] = $fila['password'];
        $_SESSION['tipo'] = $fila['id_rol'];
        $_SESSION['estado'] = $fila['id_estado'];
        $_SESSION['nit'] = $fila['nit'];
        $_SESSION['id_esp'] = isset($fila['id_esp']) ? $fila['id_esp'] : '';
        $_SESSION['token'] = isset($fila['token']) ? $fila['token'] : '';

        // Validar el estado del usuario
        if ($_SESSION['estado'] != 3) {
            echo "<script>
                    alert('Espere a ser activado');
                    window.location.href = '../index.html';
                  </script>";
            exit();
        }

        // Redirigir según el tipo de usuario
        if (in_array($_SESSION['tipo'], [1, 2, 4])) {
            header("Location: ../model/admins/insertar_codigo_seguridad.php");
            exit();
        } elseif ($_SESSION['tipo'] == 5) {
            header("Location: ../model/pacientes/index.php");
            exit();
        }elseif ($_SESSION['tipo'] == 3) {
            header("Location: ../model/admins/insertar_codigo_seguridad.php");
            exit();}
    } else {
        // Si no se encontraron resultados o la contraseña no coincide, redirigir a la página de error
        header("Location: ../error.html");
        exit();
    }
}
?>
