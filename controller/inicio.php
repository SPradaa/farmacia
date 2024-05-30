<?php
    require_once(" ../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();

    if(isset($_POST["inicio"])){
        $documento = $_POST['documento'];
        $password = $_POST['password'];

        $encriptar = htmlentities(addslashes($password));

        // Buscar en la tabla usuarios
        $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
        $sql->bindParam(':documento', $documento, PDO::PARAM_STR);
        $sql->execute();
        $fila = $sql->fetch(PDO::FETCH_ASSOC);

        // Si no se encontró en usuarios, buscar en la tabla medicos
        if (!$fila) {
            $sql = $con->prepare("SELECT * FROM medicos WHERE docu_medico = :documento");
            $sql->bindParam(':documento', $documento, PDO::PARAM_STR);
            $sql->execute();
            $fila = $sql->fetch(PDO::FETCH_ASSOC);

            // Ajuste de nombres de columnas de medicos
            if ($fila) {
                $fila['documento'] = $fila['docu_medico'];
                $fila['nombre'] = $fila['nombre_comple'];
                // Añadir nit si no existe
                if (!isset($fila['nit'])) {
                    $fila['nit'] = null;
                }
            }
        }

        if ($fila && password_verify($encriptar, $fila['password'])) {
            $_SESSION['documento'] = $fila['documento'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['apellido'] = isset($fila['apellido']) ? $fila['apellido'] : ''; // Si no hay apellido en la tabla medicos
            $_SESSION['direccion'] = isset($fila['direccion']) ? $fila['direccion'] : ''; // Si no hay dirección en la tabla medicos
            $_SESSION['telefono'] = $fila['telefono'];
            $_SESSION['correo'] = $fila['correo'];
            $_SESSION['password'] = $fila['password'];
            $_SESSION['tipo'] = $fila['id_rol'];
            $_SESSION['nit'] = $fila['nit'];

            if (in_array($_SESSION['tipo'], [1, 2, 3, 4])) {
                header("Location: ../model/admins/insertar_codigo_seguridad.php");
                exit();
            } elseif ($_SESSION['tipo'] == 5) {
                header("Location: ../model/pacientes/index.php");
                exit();
            }
        } else {
            header("Location: ../error.html");
            exit();
        }
    }