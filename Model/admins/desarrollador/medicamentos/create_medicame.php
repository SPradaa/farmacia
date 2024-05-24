<?php
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>

<?php

if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
    $nombre= $_POST['nombre'];
    $id_cla= $_POST['id_cla'];
    $cantidad= $_POST['cantidad'];
    $medida_cant= $_POST['medida_cant'];
    $id_lab= $_POST['id_lab'];
    $f_vencimiento= $_POST['f_vencimiento'];
    $lote= $_POST['lote'];
    $id_estado= $_POST['id_estado'];

    $sql= $con -> prepare ("SELECT * FROM medicamentos WHERE nombre='$nombre'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

    if ($fila){
       echo '<script>alert ("EL MEDICAMENTO YA EXISTE //CAMBIELO//");</script>';
       echo '<script>window.location="create_medicame.php"</script>';
    }
    else
 
   if ( $nombre==""|| $id_cla=="" || $cantidad=="" || $medida_cant=="" || $id_lab=="" || $f_vencimiento=="" || $lote=="" || $id_estado=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="create_medicame.php"</script>';
    }
    else
    {
      $insertSQL = $con->prepare("INSERT INTO medicamentos(nombre, id_cla, cantidad, medida_cant, id_lab, f_vencimiento, lote, id_estado) VALUES('$nombre', '$id_cla', '$cantidad', '$medida_cant', '$id_lab', '$f_vencimiento', '$lote', '$id_estado')");
      $insertSQL -> execute();
      echo '<script> alert("REGISTRO EXITOSO");</script>';
      echo '<script>window.location="index_medicame.php"</script>';
      
  } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Medicamentos</title>
    <link rel="stylesheet" href="../../css/usua.css">
</head>
<body>

    <div class="login-box">
        <h1>Crear Medicamento</h1>

        <form method="post" name="form1" id="form1"  autocomplete="off"> 

            <input type="text" name="nombre" id="nombre" pattern="[a-zA-Z0-9 ]{3,30}" placeholder="Ingrese el nombre del medicamento" title="El nombre debe tener solo letras">

            <select name="id_cla">
                <option value ="">Seleccione el Tipo de Medicamento</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from tipo_medicamento");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_cla'] . ">"
                     . $fila['clasificacion'] . "</option>";
                } 
                ?>
            </select>

            <input type="text" name="cantidad" id="cantidad" pattern="[0-9a-zA-Z]{4,20}" placeholder="Ingrese la cantidad" title="La cantidad debe tener numeros y letras">

            <input type="text" name="medida_cant" id="medida_cant" pattern="[0-9a-zA-Z]{5,30}" placeholder="Ingrese la cantidad de medida" title="La cantidad de medida debe tener solo letras y numeros">

            <select name="id_lab">
                <option value ="">Seleccione el laboratorio</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from laboratorio");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_lab'] . ">"
                     . $fila['laboratorio'] . "</option>";
                } 
                ?>
            </select>

            <label for="f_vencimiento">Fecha de Vencimiento</label>
            <input type="date" name="f_vencimiento" id="f_vencimiento">
            
             <input type="text" name="lote" id="lote" pattern="[0-9A-Za-z]{4,8}" placeholder="Ingrese el lote del medicamento" title="Debe tener numeros y letras (7)">
             <br><br>

             <select name="id_estado">
                <option value ="">Seleccione el estado del medicamento</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from estados where id_estado =1 OR id_estado =2 OR id_estado =7 OR id_estado =8");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_estado'] . ">"
                     . $fila['estado'] . "</option>";
                } 
                ?>
            </select>

             <input type="submit" name="inicio" value="Crear">
            <input type="hidden" name="MM_insert" value="formreg">
            </form>
    </div>
              
</body>
</html>