<?php
session_start();

function validarSesion() {
    if (isset($_POST['cerrar_sesion'])) {

        session_unset();
        session_destroy();
        exit();
    }

    if (!isset($_SESSION['documento'])) {
        header("Location: ../../../login.html");
        exit();
    }

    $tiempoDeInactividad = 600; // tiempo en segundos (10 minutos)
    if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad']) > $tiempoDeInactividad) {
        session_unset();
        session_destroy();
        header("Location: ../../login.html");
        exit();
    }

    $_SESSION['ultima_actividad'] = time();
}
?>
