<?php
    session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();

    $sql = $con -> prepare ("SELECT * FROM medicos, roles, estados, especializacion, t_documento
    WHERE medicos.id_rol = roles.id_rol AND medicos.id_estado = estados.id_estado 
    AND medicos.id_esp = especializacion.id_esp AND medicos.id_doc = t_documento.id_doc 
    AND medicos.docu_medico = '".$_GET['docu_medico']."'");
    $sql -> execute();
    $usua =$sql -> fetch();
?>

<?php

if(isset($_POST["update"]))
 {
    $id_doc= $_POST['id_doc'];
    $docu_medico= $_POST['docu_medico'];
    $nombre_comple= $_POST['nombre_comple'];
    $correo= $_POST['correo'];
    $telefono= $_POST['telefono'];
    $id_rol= $_POST['id_rol'];
    $id_estado= $_POST['id_estado'];
    $id_esp= $_POST['id_esp'];
 
   if ($id_doc=="" ||  $docu_medico=="" || $nombre_comple=="" || $telefono=="" || $correo==""|| $id_rol=="" || $id_estado=="" || $id_esp=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="index_medico.php"</script>';
    }
    else
    {
      $insertSQL = $con->prepare("UPDATE medicos SET id_doc = '$id_doc', docu_medico = '$docu_medico', 
      nombre_comple = '$nombre_comple', telefono = '$telefono',correo = '$correo', 
      id_rol = '$id_rol', id_estado = '$id_estado', id_esp = '$id_esp' WHERE docu_medico = '".$_GET['docu_medico']."'");
      $insertSQL -> execute();
      echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';
      echo '<script>window.location="index_medico.php"</script>';
      
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
    <link rel="stylesheet" href="../../css/updateu.css">
</head>
<body>
    <div class="formulario">
        <h1>Editar Medico</h1>
        <form method="POST" name="formreg" autocomplete="off">
        <select name="id_doc">
            <option value="<?php echo $usua['id_doc']?>"><?php echo $usua['tipo']?></option>
            <?php
          
            $control = $con -> prepare ("SELECT * From t_documento");$control -> execute();
            while ($fila = $control -> fetch(PDO::FETCH_ASSOC))
            {
            echo "<option value=" . $fila['id_doc'] . ">" . $fila['tipo'] . "</option>";
            }
            ?>
            </select>
            <div class="campos">
                <input type="text" name="docu_medico" value="<?php echo $usua['docu_medico']?>" readonly> 
            </div>
            <div class="campos">
                <input type="text" name="nombre_comple" pattern="[a-zA-Z ]{8,50}" title="El nombre debe tener solo letras" value="<?php echo $usua['nombre_comple']?>">
                
                <input type="text" name="telefono" pattern="[0-9]{10}" title="El telefono debe tener solo numeros (10 digitos)" value="<?php echo $usua['telefono']?>">
            </div>
            <div class="campos">
                <input type="text" name="correo" pattern="[a-zA-Z0-9.-@]{7,30}" title="El correo debe tener minimo 7 caracteres" value="<?php echo $usua['correo']?>">
            </div>
            <select name="id_rol">
            <option value="<?php echo $usua['id_rol']?>"><?php echo $usua['rol']?></option>
            <?php
          
            $control = $con -> prepare ("SELECT * From roles where id_rol =3");
            $control -> execute();
            while ($fila = $control -> fetch(PDO::FETCH_ASSOC))
            {
            echo "<option value=" . $fila['id_rol'] . ">" . $fila['rol'] . "</option>";
            }
            ?>
            </select>
            <select name="id_estado">
            <option value="<?php echo $usua['id_estado']?>"><?php echo $usua['estado']?></option>
            <?php
          
            $control = $con -> prepare ("SELECT * From estados where id_estado =3 OR id_estado =4");
            $control -> execute();
            while ($fila = $control -> fetch(PDO::FETCH_ASSOC))
            {
            echo "<option value=" . $fila['id_estado'] . ">" . $fila['estado'] . "</option>";
            }
            ?>
            </select>
            <select name="id_esp">
            <option value="<?php echo $usua['id_esp']?>"><?php echo $usua['especializacion']?></option>
            <?php
          
            $control = $con -> prepare ("SELECT * From especializacion");$control -> execute();
            while ($fila = $control -> fetch(PDO::FETCH_ASSOC))
            {
            echo "<option value=" . $fila['id_esp'] . ">" . $fila['especializacion'] . "</option>";
            }
            ?>
            </select>

            <br><br>
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
</body>
</html>