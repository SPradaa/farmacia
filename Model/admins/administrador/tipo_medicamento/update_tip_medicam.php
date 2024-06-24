<?php
    session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();

    $sql = $con -> prepare ("SELECT * FROM tipo_medicamento WHERE tipo_medicamento.id_cla = '".$_GET['id_cla']."'");
    $sql -> execute();
    $usua =$sql -> fetch();
?>

<?php

if(isset($_POST["update"]))
 {
    $clasificacion = $_POST['clasificacion'];

    if ($clasificacion=="")
    {
    echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
    echo '<script>window.location="index_medicam.php"</script>';
    }
    else{

    $insertSQL = $con -> prepare("UPDATE tipo_medicamento SET clasificacion = '$clasificacion' WHERE id_cla = '".$_GET['id_cla']."'");      
    $insertSQL->execute();
    echo '<script>alert ("Actualización exitosa");</script>';
    echo '<script>window.location="index_medicam.php"</script>';
}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar</title>
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../../css/tip_usua.css">
    <title>Crear Medicamentos</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- Añade jQuery -->
    <script>
        function validateField(regex, input, errorMessage) {
            const value = input.value;
            const isValid = regex.test(value);
            input.setCustomValidity(isValid ? "" : errorMessage);
            input.reportValidity();
            return isValid;
        }

        $(document).ready(function() {

            $("#clasificacion").on("input", function() {
                validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){5,30}$/, this, "Ingrese un nombre válido (solo letras)");
            });
        });

        function validateForm() {
            const isClasificacionnValid = validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){5,30}$/, document.getElementById("clasificacion"), "Ingrese un nombre válido (solo letras)");

            return isClasificacionValid;
        }
    </script>
    <style>
   * {
	box-sizing: border-box;
	margin: 0;
	padding: 0;	
	font-family: Raleway, sans-serif;
    }

    body {
        background: linear-gradient(90deg, #ffffff, rgb(149, 238, 243));	
    }
.login-box {
    width: 550px;
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: -1px 1px 5px 7px rgba(0,0,0,0.37);
    -webkit-box-shadow: -1px 1px 5px 7px rgba(0,0,0,0.37);
    -moz-box-shadow: -1px 1px 5px 7px rgba(0,0,0,0.37);
    margin-left: 1px;
    justify-content: center;
    margin-top: 2px;
}
img{
    width: 110px;
    height: 90px;
    margin-left: -4%;
    margin-top: -15px;
    margin-bottom: 30px;
}
.login-box h1 {
    margin: 0 0 20px;
    margin-left: 100px;
    font-size: 25px;
    color: #333;
    margin-bottom: 70px;
    margin-top: -100px;
}

.login-box input[type="text"] {
    width: calc(80% - 5px);
    padding: 10px;
    margin-bottom: 20px;
    border: 2px solid #02e8f8;
    border-radius: 5px;
    box-sizing: border-box;
    display: inline-block;
    margin-right: 20px;
    margin-left: 50px;
    font-size: 16px;
}
.login-box .form-row {
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
}

/* Ajustes para los botones */
.login-box .button {
    width: 30%; /* Cambia el ancho a tu preferencia */
    background-color: aqua; /* Color rojo para el botón Cancelar */
    border: 1px solid #ccc;
    border-radius: 5px;
    color: black;
    padding: 13px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    margin-left: 27.5%; /* Agrega margen entre los botones */
}

.login-box input[type="submit"] {
    width: 30%; /* Cambia el ancho a tu preferencia */
    background-color: #046bcc; /* Color azul para el botón Crear Usuario */
    border: 1px solid #ccc;
    border-radius: 5px;
    color: #fff;
    padding: 13px;
    font-size: 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    margin-left: 49.5%; /* Elimina el margen izquierdo */
    margin-top: -60px;
}

/* Alineación de los botones al centro */
.login-box .form-row {
    display: flex;
    justify-content: center;
}

/* Estilo al pasar el mouse sobre los botones */
.login-box .button:hover,
.login-box input[type="submit"]:hover {
    background-color: #057ef0; /* Cambia el color de fondo al pasar el mouse */
}

/* Estilo específico para el botón "Cancelar" al pasar el mouse */
.login-box .button:hover {
    background-color: rgb(59, 243, 243); /* Cambia el color de fondo al pasar el mouse */
   
}


form .btn{
    width: 20%;
    height: 44px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #fff;
    display: block;
    font-size: 14px;
    background: #ff4d4d;
    margin-top: 24px;
    margin-bottom:-30px;
    padding: 12px 12px;
    margin-right: 10px;
    outline: medium none;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    margin-left: 100px;
}
form .btn:hover {
    background-color: #ff4d2d;
    color: #fff;
    border: none;
}

@media screen and (max-width: 767px) {
    .login-box h1 {
        margin-top: -100px; /* Ajusta este valor según sea necesario */
        margin-bottom: 70px; /* Ajusta este valor según sea necesario */
        font-size: 18px; /* Tamaño de fuente más pequeño para pantallas pequeñas */
    }
}

</style>
</head>
<body>
    <div class="login-box">
    <img src="../../../../assets/img/log.farma.png">
        <h1>Editar Tipo de Medicamento</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="campos">
                <input type="text" name="clasificacion" id="clasificacion" value="<?php echo $usua['clasificacion']?>">
            </div>
            <a href="index_medicam.php" class="btn btn__danger">Cancelar</a>
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
</body>
</html>