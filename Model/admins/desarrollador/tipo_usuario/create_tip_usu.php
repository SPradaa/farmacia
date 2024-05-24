<?php

    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>

<?php

if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
    $rol = $_POST['rol'];

    $sql= $con -> prepare ("SELECT * FROM roles WHERE rol='$rol'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

    if ($fila){
    echo '<script>alert ("TIPO DE USUARIO YA EXISTE //CAMBIELO//");</script>';
    echo '<script>window.location="create_tip_usu.php"</script>';
    }
    else

    if ($rol=="")
    {
    echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
    echo '<script>window.location="create_tip_usu.php"</script>';
    }
    else
    {
    $insertSQL = $con->prepare("INSERT INTO roles(rol) VALUES('$rol')");
    $insertSQL -> execute();
    echo '<script> alert("Tipo de Usuario registrado exitosamente");</script>';
    echo '<script>window.location="index_tip_usu.php"</script>';
    
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/tip_usua.css">
    <title>Registro</title>
</head>
<body>
    <div class="formulario">
        <h1>Crear Tipo de Usuario</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="campos">
                <input type="text" name="id_rol" placeholder="Ingrese el id_rol" disabled>  
            </div>
            <div class="campos">
                <input type="text" name="rol" pattern="[a-zA-Z ]{3,20}" title="El rol debe tener solo letras" placeholder="Ingrese el Rol">
            </div>
            
            
            <input type="submit" name="inicio" value="Crear">
            <input type="hidden" name="MM_insert" value="formreg">
        </form>
        

</body>
</html>