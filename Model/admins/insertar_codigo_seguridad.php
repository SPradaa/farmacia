<?php
require_once("../../db/connection.php"); 
$db = new Database();
$con = $db->conectar();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="../../assets/img/logof.png" rel="icon">
    <link href="../../assets/img/logof.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../../assets/css/seguridad.css">
</head>
<body>

<div class="regresar">
    <button onclick="goBack()" class="return">
        <span class="btxt">Regresar</span><i class="animate"></i>
    </button>
</div>

<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form method="POST" name="form1" id="form1" action="../../controller/validacion.php" autocomplete="off" class="login">
                <div class="login__field">
                    <h2>Código de Seguridad</h2>
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="codigo" placeholder="Ingrese el código único de la empresa" required>
                </div>
                <input type="submit" name="inicio" class="button login__submit" value="Iniciar Sesión">
            </form>
        </div>
    </div>
</div>

<script>
    function goBack() {
        window.location.href = '../../login.html';
    }
</script>

</body>
</html>
