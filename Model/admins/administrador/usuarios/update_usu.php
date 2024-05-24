<?php
    session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();

    $sql = $con -> prepare ("SELECT * FROM usuarios, roles, t_documento, ciudad, empresas, rh, estados
    WHERE usuarios.id_rol = roles.id_rol AND usuarios.id_doc = t_documento.id_doc AND usuarios.id_ciudad = ciudad.id_ciudad AND
    usuarios.nit = empresas.nit AND usuarios.id_rh = rh.id_rh AND usuarios.id_estado = estados.id_estado AND usuarios.documento = '".$_GET['id']."'");
    $sql -> execute();
    $usua =$sql -> fetch();
?>

<?php

if(isset($_POST["update"]))
 {
    $documento= $_POST['documento'];
    $id_doc= $_POST['id_doc'];
    $nombre= $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $nit= $_POST['nit'];
    $id_rh= $_POST['id_rh'];
    $telefono= $_POST['telefono'];
    $correo= $_POST['correo'];
    $id_ciudad= $_POST['id_ciudad'];
    $direccion=$_POST['direccion'];
    $id_rol= $_POST['id_rol'];
    $id_estado= $_POST['id_estado'];
 
   if ($documento=="" || $id_doc=="" || $nombre=="" || $apellido=="" || $nit=="" || $id_rh=="" || $telefono=="" || $correo=="" || $id_ciudad=="" || $direccion=="" || $id_estado=="" || $id_estado=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="index_usu.php"</script>';
    }
    else
    {
      $insertSQL = $con->prepare("UPDATE usuarios SET documento = '$documento', id_doc = '$id_doc', 
      nombre = '$nombre', apellido = '$apellido', nit = '$nit', id_rh = '$id_rh', telefono = '$telefono',
      correo = '$correo', id_ciudad = '$id_ciudad', direccion = '$direccion', 
      id_rol = '$id_rol', id_estado = '$id_estado' WHERE documento = '".$_GET['id']."'");
      $insertSQL -> execute();
      echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';
      echo '<script>window.location="index_usu.php"</script>';
      
  } 
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/updateu.css">
    <title>Actualizar Usuario</title>

</head>
<body>
    <div class="formulario">
        <h1>Editar Usuario</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="campos">
                <select name="id_doc">
                    <option value="<?php echo $usua['id_doc']?>"><?php echo $usua['tipo']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM t_documento");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_doc'] . ">" . $fila['tipo'] . "</option>";
                        }
                    ?>
                </select>
                <input type="text" name="documento" value="<?php echo $usua['documento']?>" readonly> 
            </div>
            <div class="campos">
                <input type="text" name="nombre" pattern="[a-zA-Z ]{3,30}" title="El nombre debe tener solo letras" value="<?php echo $usua['nombre']?>">
                <input type="text" name="apellido" pattern="[a-zA-Z ]{4,30}" title="El apellido debe tener solo letras" value="<?php echo $usua['apellido']?>">
            </div>
            <div class="campos">
                <input type="text" name="telefono" pattern="[0-9]{10}" title="El teléfono debe tener solo números (10 dígitos)" value="<?php echo $usua['telefono']?>">
                <input type="text" name="correo" pattern="[a-zA-Z0-9.-@]{7,30}" title="El correo debe tener mínimo 7 caracteres" value="<?php echo $usua['correo']?>">
            </div>
            <div class="campos">
                <select name="id_ciudad">
                    <option value="<?php echo $usua['id_ciudad']?>"><?php echo $usua['ciudad']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM ciudad");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_ciudad'] . ">" . $fila['ciudad'] . "</option>";
                        }
                    ?>
                </select>
                <input type="text" name="direccion" pattern="[a-zA-Z0-9,.-# ]{5,30}" title="La dirección debe tener mínimo 5 caracteres" value="<?php echo $usua['direccion']?>">
            </div>
            <div class="campos">
                <select name="nit">
                    <option value="<?php echo $usua['nit']?>"><?php echo $usua['empresa']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM empresas");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['nit'] . ">" . $fila['empresa'] . "</option>";
                        }
                    ?>
                </select>
                <select name="id_rh">
                    <option value="<?php echo $usua['id_rh']?>"><?php echo $usua['rh']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM rh");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_rh'] . ">" . $fila['rh'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="campos">
                <select name="id_rol">
                    <option value="<?php echo $usua['id_rol']?>"><?php echo $usua['rol']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM roles WHERE id_rol IN (2, 4, 5)");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_rol'] . ">" . $fila['rol'] . "</option>";
                        }
                    ?>
                </select>
                <select name="id_estado">
                    <option value="<?php echo $usua['id_estado']?>"><?php echo $usua['estado']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM estados WHERE id_estado IN (3, 4)");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_estado'] . ">" . $fila['estado'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <br><br>
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
</body>
</html>
