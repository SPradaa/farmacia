<?php
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>

<?php

if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
    $id_doc= $_POST['id_doc'];
    $docu_medico= $_POST['docu_medico'];
    $nombre_comple= $_POST['nombre_comple'];
    $correo= $_POST['correo'];
    $telefono= $_POST['telefono'];
    $clave= $_POST['password'];
    $id_rol= $_POST['id_rol'];
    $id_estado= $_POST['id_estado'];
    $id_esp= $_POST['id_esp'];

    $sql= $con -> prepare ("SELECT * FROM medicos WHERE docu_medico='$docu_medico'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

    if ($fila){
       echo '<script>alert ("EL DOCUMENTO YA EXISTE //CAMBIELO//");</script>';
       echo '<script>window.location="create_medico.php"</script>';
    }
    else
 
   if ($id_doc=="" ||  $docu_medico=="" || $nombre_comple=="" || $telefono=="" || $correo=="" || $clave=="" || $id_rol=="" || $id_estado=="" || $id_esp=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="create_medico.php"</script>';
    }
    else
    {
      $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
      $insertSQL = $con->prepare("INSERT INTO medicos(id_doc, docu_medico, nombre_comple, telefono, correo, password, id_rol, id_estado, id_esp) VALUES('$id_doc', '$docu_medico', '$nombre_comple', '$telefono', '$correo', '$pass_cifrado', '$id_rol', '$id_estado', '$id_esp')");
      $insertSQL -> execute();
      echo '<script> alert("REGISTRO EXITOSO");</script>';
      echo '<script>window.location="index_medico.php"</script>';
      
  } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Medico</title>
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../css/crear_medico.css">
    <style>
        @media (max-width: 768px){
            .regresar{
                margin-top: -7px;
            }
            input[type="submit"]{
                margin-top: 15px;
                margin-bottom: 15px;
                margin-left: 85px;
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
        <h1>Crear Medicos</h1>

        <form method="post" name="form1" id="form1"  autocomplete="off"> 

        <div class="row">
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
        
            <input type="text" name="docu_medico" id="docu_medico" placeholder="Digite su Documento" required>
            </div>
            <div class="row">
            <input type="text" name="nombre_comple" id="nombre_comple" placeholder="Ingrese su Nombre Completo" required>

            <input type="text" name="telefono" id="telefono" placeholder="Ingrese su Telefono" required>
            </div>
            <div class="row">
            <input type="text" name="correo" id="correo" placeholder="Ingrese su Correo">

            <input type="text" name="direccion" id="direccion" placeholder="Ingrese la dirección">
            </div>
            <div class="row">
             <input type="password" name="password" id="password" placeholder="Ingrese la Contraseña">

             <select name="id_rol">
                <option value ="">Seleccione el tipo de Usuario</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from roles where id_rol =3");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_rol'] . ">"
                     . $fila['rol'] . "</option>";
                } 
                ?>
            </select>
            </div>
            <div class="row">
            <select name="id_estado">
                <option value ="">Seleccione el Estado del Medico</option>
                
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

            <select name="id_esp">
                <option value ="">Seleccione la especialización del Medico</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from especializacion");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_esp'] . ">"
                     . $fila['especializacion'] . "</option>";
                } 
                ?>
            </select>
            </div>

             <input type="submit" name="inicio" value="Crear Medico">
            <input type="hidden" name="MM_insert" value="formreg">
            </form>
    </div>
    <script>
    // Guardar los valores de los campos en el Local Storage antes de redirigir
    $(document).on('submit', '#form1', function(){
        var formValues = $(this).serializeArray();
        localStorage.setItem('formValues', JSON.stringify(formValues));
    });

    // Cargar los valores guardados del Local Storage cuando la página se carga
    $(document).ready(function(){
        var formValues = JSON.parse(localStorage.getItem('formValues'));
        if(formValues){
            $.each(formValues, function(index, element){
                $('[name="'+element.name+'"]').val(element.value);
            });
            localStorage.removeItem('formValues');
        }
    });
    </script>
              
</body>
</html>