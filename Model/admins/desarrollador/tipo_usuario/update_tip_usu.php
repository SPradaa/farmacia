<?php
    session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();

    $sql = $con -> prepare ("SELECT * FROM roles WHERE roles.id_rol = '".$_GET['id_rol']."'");
    $sql -> execute();
    $usua =$sql -> fetch();
?>

<?php

if(isset($_POST["update"]))
 {
    $rol = $_POST['rol'];

    if ($rol=="")
    {
    echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
    echo '<script>window.location="index_tip_usu.php"</script>';
    }
    else{

    $insertSQL = $con -> prepare("UPDATE roles SET rol = '$rol' WHERE id_rol = '".$_GET['id_rol']."'");      
    $insertSQL->execute();
    echo '<script>alert ("Actualización exitosa");</script>';
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
    <title>Actualizar</title>
    <link rel="stylesheet" href="../../css/tip_usua.css">
</head>
<body>
    <div class="formulario">
        <h1>Editar Tipo de Usuario</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="campos">
                <input type="text" name="id_rol" value="<?php echo $usua['id_rol']?>" readonly> 
            </div>
            <div class="campos">
                <input type="text" name="rol" pattern="[a-zA-Z ]{3,20}" title="El rol de usuario debe tener solo letras" value="<?php echo $usua['rol']?>">
            </div>
            
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
</body>
</html>