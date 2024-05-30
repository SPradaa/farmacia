<?php
    require_once(" ../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();

    if($_POST ["inicio"]){
        $documento = $_POST['documento'];
        $password = $_POST['password'];

        $encriptar = htmlentities(addslashes($_POST['password']));

        // Modificar la consulta para incluir los datos de municipio y departamento
        $sql = $con->prepare("
        SELECT u.*, m.municipio, d.depart, d.id_depart 
        FROM usuarios u
        INNER JOIN municipios m ON u.id_municipio = m.id_municipio
        INNER JOIN departamentos d ON m.id_depart = d.id_depart
        WHERE u.documento = :documento
    ");

    $sql->bindParam(':documento', $documento);
    $sql->execute();
    $fila = $sql->fetch(PDO::FETCH_ASSOC);

    if (password_verify($encriptar, $fila['password'])) {
        // Establecer datos de usuario en la sesión
        $_SESSION['documento'] = $fila['documento'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['apellido'] = $fila['apellido'];
        $_SESSION['direccion'] = $fila['direccion'];
        $_SESSION['id_municipio'] = $fila['id_municipio'];
        $_SESSION['municipio'] = $fila['municipio'];
        $_SESSION['id_depart'] = $fila['id_depart'];
        $_SESSION['depart'] = $fila['depart'];
        $_SESSION['telefono'] = $fila['telefono'];
        $_SESSION['correo'] = $fila['correo'];
        $_SESSION['password'] = $fila['password'];
        $_SESSION['tipo'] = $fila['id_rol'];
        $_SESSION['nit'] = $fila['nit'];


        if($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 2 || $_SESSION['tipo'] == 3 || $_SESSION['tipo'] == 4 ){
            header("Location: ../model/admins/insertar_codigo_seguridad.php");
            exit();
        }

        if($_SESSION['tipo'] == 5 ){
            header("Location: ../model/pacientes/index.php");
            exit();
        }
    }

    else{
        header("location: ../error.html");
        exit();
    }
}