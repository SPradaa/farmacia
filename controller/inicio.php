<?php
    require_once(" ../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();

    if($_POST ["inicio"]){
        $documento = $_POST['documento'];
        $password = $_POST['password'];

        $encriptar = htmlentities(addslashes($_POST['password']));

    $sql = $con -> prepare("SELECT * FROM  usuarios WHERE documento = '$documento'");
    $sql -> execute();
    $fila = $sql -> fetch();

    if(password_verify($encriptar, $fila['password']))
    {
        $_SESSION['documento'] = $fila['documento'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION[ 'apellido'] = $fila['apellido'];
        $_SESSION[ 'direccion'] = $fila['direccion'];
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