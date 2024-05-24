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
    <link rel="stylesheet" href="../../css/tip_usua.css">
</head>
<body>
    <div class="formulario">
        <h1>Editar Tipo de Medicamento</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="campos">
                <input type="text" name="id_cla" value="<?php echo $usua['id_cla']?>" readonly> 
            </div>
            <div class="campos">
                <input type="text" name="clasificacion" pattern="[a-zA-Z ]{3,30}" title="El medicamento debe tener solo letras" value="<?php echo $usua['clasificacion']?>">
            </div>
            
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
</body>
</html>