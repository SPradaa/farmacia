<?php

    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>

<?php

if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
    $tipo = $_POST['tipo'];

    $sql= $con -> prepare ("SELECT * FROM t_documento WHERE tipo='$tipo'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

    if ($fila){
    echo '<script>alert ("TIPO DE Documento YA EXISTE //CAMBIELO//");</script>';
    echo '<script>window.location="insert_docu.php"</script>';
    }
    else

    if ($tipo=="")
    {
    echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
    echo '<script>window.location="insert_docu.php"</script>';
    }
    else
    {
    $insertSQL = $con->prepare("INSERT INTO t_documento(tipo) VALUES('$tipo')");
    $insertSQL -> execute();
    echo '<script> alert("Tipo de Documento registrado exitosamente");</script>';
    echo '<script>window.location="index_docu.php"</script>';
    
    } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipo de Documento</title>
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../../css/tip_usua.css">
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

            $("#tipo").on("input", function() {
                validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){5,30}$/, this, "Ingrese un nombre válido (solo letras)");
            });
        });

        function validateForm() {
            const isTipoValid = validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){5,30}$/, document.getElementById("tipo"), "Ingrese un nombre válido (solo letras)");

            return isTipoValid;
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
.login-box h2 {
    margin: 0 0 20px;
    margin-left: 23%;
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
    .login-box h2 {
        margin-left: 100px;
        margin-top: -100px; /* Ajusta este valor según sea necesario */
        margin-bottom: 70px; /* Ajusta este valor según sea necesario */
        font-size: 20px; /* Tamaño de fuente más pequeño para pantallas pequeñas */
    }
}

</style>
</head>
<body>
<div class="login-box">
<img src="../../../../assets/img/log.farma.png">
        <h2>Crear Documento de Identidad</h2>
        <form action="" method="post">
            <div class="campos">
                <input type="text" name="tipo" id="tipo" placeholder="Tipo de Documento" class="
                input__text">
            </div>

            <a href="index_docu.php" class="btn btn__danger">Cancelar</a>
            <input type="submit" name="inicio" value="Crear">
            <input type="hidden" name="MM_insert" value="formreg">
        </form>
    </div>
</body>
</html>