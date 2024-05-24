<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $docu_medico=$_POST['autorizaciones'];
        $autorizaciones=$_POST['autorizacionSSes'];
        


        if(!empty($docu_medico) && !empty($autorizaciones)){

                $consulta_insert=$con->prepare('INSERT INTO autorizaciones(docu_medico,autorizaciones) VALUES (:docu_medico,:autorizaciones)');
                $consulta_insert->execute(array(':docu_medico' =>$docu_medico,':autorizaciones' =>$autorizaciones));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_automedicam.php"</script>';

                }
                
                else{
                    echo "<scrip> alert ('Los campos estan vacios');</scrip>";
                }
           }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva autorización</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear </h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="autorizacion" placeholder="Autorización" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_automedicam.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>