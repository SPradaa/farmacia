<?php
    // session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    
    
require_once("../../../../controller/seg.php");
validarSesion();

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
    <link rel="stylesheet" href="../../desarrollador/css/edi_usu.css">
    <style>
        @media (max-width: 768px){
            .regresar{
                margin-top: 7px;
            }
            input[type="submit"]{
                margin-top: -1px;
                margin-bottom: 2px;
                margin-left: 105px;
            }
        }
    </style>
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
            $("#docu_medico").on("input", function() {
                validateField(/^\d{8,10}$/, this, "Debe ingresar solo números (8 a 10 dígitos)");
            });

            $("#nombre_comple").on("input", function() {
                validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){5,40}$/, this, "Ingrese un nombre válido (solo letras)");
            });

            $("#telefono").on("input", function() {
                validateField(/^\d{10}$/, this, "Debe ingresar solo números (10 dígitos)");
            });

            $("#correo").on("input", function() {
                validateField(/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/, this, "Ingrese un correo válido que lleve '@'");
            });

            $("#direccion").on("input", function() {
                validateField(/^([a-zA-Z0-9#.,\-_áéíóúÁÉÍÓÚñÑ\s]){5,30}$/, this, "Ingrese una dirección válida");
            });

            $("#password").on("input", function() {
                validateField(/^[a-zA-Z0-9]{8}$/, this, "Debe ingresar solo números y letras (8 caracteres)");
            });
        });

        function validateForm() {
            const isDocumentoValid = validateField(/^\d{8,10}$/, document.getElementById("documento"), "Debe ingresar solo números (8 a 10 dígitos)");
            const isNombreValid = validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){5,40}$/, document.getElementById("nombre"), "Debe ingresar solo letras");const isApellidoValid = validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, document.getElementById("apellido"), "Debe ingresar solo letras");
            const isTelefonoValid = validateField(/^\d{10}$/, document.getElementById("telefono"), "Debe ingresar solo números (10 dígitos)");
            const isCorreoValid = validateField(/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/, document.getElementById("correo"), "Debe ser un correo válido que lleve '@'");
            const isDireccionValid = validateField(/^([a-zA-Z0-9#.,\-_áéíóúÁÉÍÓÚñÑ\s]){5,30}$/, document.getElementById("direccion"), "Debe ser una dirección válida");
            const isPasswordValid = validateField(/^[a-zA-Z0-9]{8}$/, document.getElementById("password"), "Debe ingresar solo números y letras (8 caracteres)");

            return isDocumentoValid && isNombreValid && isTelefonoValid && isCorreoValid && isDireccionValid && isPasswordValid;
        }
    </script>
    <style>
        .login-box{
            margin-top: 9px;
        }
    </style>
</head>
<body>
<div class="regresar">
        <div class="col-md-6">
            <form action="index_medico.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
        </div>
        <div class="login-box">
        <img src="../../../../assets/img/log.farma.png">
        <h1>Editar Medico</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="row">
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
                <input type="text" name="docu_medico" id="docu_medico" value="<?php echo $usua['docu_medico']?>" readonly> 
            </div>
            <div class="row">
                <input type="text" name="nombre_comple" id="nombre_comple" value="<?php echo $usua['nombre_comple']?>" readonly>
                
                <input type="text" name="telefono" id="telefono" value="<?php echo $usua['telefono']?>">
            </div>
            <div class="row">
                <input type="text" name="correo" id="correo" value="<?php echo $usua['correo']?>">
            
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
            </div>
            <div class="row">
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
        </div>
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
</body>
</html>