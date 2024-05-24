<?php
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>

<?php

if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
    $documento= $_POST['documento'];
    $id_doc= $_POST['id_doc'];
    $nombre= $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $id_eps= $_POST['id_eps'];
    $id_rh= $_POST['id_rh'];
    $telefono= $_POST['telefono'];
    $correo= $_POST['correo'];
    $id_ciudad= $_POST['id_ciudad'];
    $direccion=$_POST['direccion'];
    $clave= $_POST['password'];
    $id_rol= $_POST['id_rol'];
    $id_estado= $_POST['id_estado'];

    $sql= $con -> prepare ("SELECT * FROM usuarios WHERE documento='$documento'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

    if ($fila){
       echo '<script>alert ("EL DOCUMENTO YA EXISTE //CAMBIELO//");</script>';
       echo '<script>window.location="create_usu.php"</script>';
    }
    else
 
   if ($documento=="" || $id_doc=="" || $nombre=="" || $apellido=="" || $id_eps=="" || $id_rh=="" || $telefono=="" || $correo=="" || $id_ciudad=="" || $direccion=="" || $clave=="" || $id_rol=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="create_usu.php"</script>';
    }
    else
    {
      $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
      $insertSQL = $con->prepare("INSERT INTO usuarios(documento, id_doc, nombre, apellido, id_eps, id_rh, telefono, correo, id_ciudad, direccion, password, id_rol, id_estado) VALUES('$documento', '$id_doc', '$nombre', '$apellido', '$id_eps', '$id_rh', '$telefono', '$correo', '$id_ciudad', '$direccion', '$pass_cifrado', '$id_rol', '$id_estado')");
      $insertSQL -> execute();
      echo '<script> alert("REGISTRO EXITOSO");</script>';
      echo '<script>window.location="index_usu.php"</script>';
      
  } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
    <link rel="stylesheet" href="../../css/crear_usu.css">

</head>
<body>

    <div class="login-box">
    <div class="logo-title">
        <img src="../../../../assets/img/log.farma.png">
        <h1>Crear Usuario</h1>
</div>

        <form method="post" name="form1" id="form1"  autocomplete="off"> 

        <div class="form-row">
        <select name="id_doc">
                <option value ="">Seleccione el Tipo de Documento</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from t_documento");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_doc'] . ">"
                     . $fila['tipo'] . "</option>";
                } 
                ?>
            </select>
        
            
            <input type="text" name="documento" id="documento" pattern="[0-9]{8,11}" placeholder="Digite su Documento" title="El documento debe tener solo números de 8 a 10 dígitos">

            <input type="text" name="nombre" id="nombre" pattern="[a-zA-Z ]{3,30}" placeholder="Ingrese su Nombre" title="El nombre debe tener solo letras">
            </div>

            <div class="form-row">
            
            <input type="text" name="apellido" id="apellido" pattern="[a-zA-ZÑñ ]{4,30}" placeholder="Ingrese su Apellido" title="El apellido debe tener solo letras">

            <select name="id_eps">
                <option value ="">Seleccione el Tipo de EPS</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from eps");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_eps'] . ">"
                     . $fila['eps'] . "</option>";
                } 
                ?>
            </select>


            <select name="id_rh">
                <option value ="">Seleccione el Tipo de Sangre</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from rh");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_rh'] . ">"
                     . $fila['rh'] . "</option>";
                } 
                ?>
            </select>
            </div>

            <div class="form-row">


            <input type="text" name="telefono" id="telefono" pattern="[0-9]{10}" placeholder="Ingrese su Telefono" title="El telefono debe tener solo numeros (10 digitos)">
            <a href="create_usu.php" class="btn btn_nuevo">Crear EPS</a>

            <input type="text" name="correo" id="correo" pattern="[0-9a-zA-Z.@]{7,30}" placeholder="Ingrese su Correo" title="El correo debe tener minimo 10 letras y numeros">
            </div>

            <div class="form-row">

            <select name="id_ciudad">
                <option value ="">Seleccione la Ciudad de Residencia</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from ciudad");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_ciudad'] . ">"
                     . $fila['ciudad'] . "</option>";
                } 
                ?>
            </select>

            <input type="text" name="direccion" id="direccion" pattern="[a-zA-Z0-9#.-]{5,30}" placeholder="Ingrese la dirección">
            
            <input type="password" name="password" id="password" pattern="[0-9A-Za-z]{8}" placeholder="Ingrese la Contraseña" title="La contraseña debe tener numeros y letras (8)">
            </div>

            <div class="form-row">

            <a href="create_usu.php" class="btn btn_nuevo">Crear Ciudad</a>
             <br><br>

             <select name="id_rol">
                <option value ="">Seleccione el tipo de Usuario</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from roles where id_rol =1 OR id_rol =2 OR id_rol =4 OR id_rol =5");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_rol'] . ">"
                     . $fila['rol'] . "</option>";
                } 
                ?>
            </select>

            <select name="id_estado">
                <option value ="">Seleccione el Estado del Usuario</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from estados where id_estado =3 OR id_estado =4");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_estado'] . ">"
                     . $fila['estado'] . "</option>";
                } 
                ?>
            </select>
            </div>

            
            <a href="index_usu.php" class="button">Cancelar</a>
             <input type="submit" name="inicio" value="Crear Usuario">
            <input type="hidden" name="MM_insert" value="formreg">
            </form>
    </div>
              
</body>
</html>