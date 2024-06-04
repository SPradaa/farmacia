<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_lab=$_POST['laboratorio'];
        $laboratorio=$_POST['laboratorio'];
        


        if(!empty($id_lab) && !empty($laboratorio)){

                $consulta_insert=$con->prepare('INSERT INTO laboratorio(id_lab,laboratorio) VALUES (:id_lab,:laboratorio)');
                $consulta_insert->execute(array(':id_lab' =>$id_lab,':laboratorio' =>$laboratorio));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_lab.php"</script>';

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
    <title>Nuevo Laboratorio</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear Laboratorio</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="laboratorio" placeholder="Laboratorio" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_lab.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>