<?php

   require_once ("db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
   session_start();
?>

<?php

   if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
   {
      $documento= $_POST['documento'];
      $id_doc= $_POST['id_doc'];
      $nombre= $_POST['nombre'];
      $apellido= $_POST['apellido'];
      $nit= $_POST['nit'];
      $id_rh= $_POST['id_rh'];
      $telefono= $_POST['telefono'];
      $correo= $_POST['correo'];
      $ciudad= $_POST['id_municipio'];
      $direccion=$_POST['direccion'];
      $clave= $_POST['password'];
      $id_rol= 5;
      $estado= 4;

      $sql= $con -> prepare ("SELECT * FROM usuarios WHERE documento='$documento'");
      $sql -> execute();
      $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
 
      if ($fila){
         echo '<script>alert ("EL DOCUMENTO YA EXISTE //CAMBIELO//");</script>';
         echo '<script>window.location="registro.php"</script>';
      }
      else
   
     if ($documento=="" || $id_doc=="" || $nombre=="" || $apellido=="" || $id_rh=="" || $telefono=="" || $correo=="" || $ciudad=="" || $direccion=="" || $clave=="" || $id_rol=="")
      {
         echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
         echo '<script>window.location="registro.php"</script>';
      }
      else
      {
        $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
        $insertSQL = $con->prepare("INSERT INTO usuarios(documento, id_doc, nombre, apellido, id_rh, telefono, correo, id_municipio, direccion, password, id_rol, id_estado, nit) VALUES('$documento', '$id_doc', '$nombre', '$apellido',  '$id_rh', '$telefono', '$correo', '$ciudad', '$direccion', '$pass_cifrado', '$id_rol', '$estado', '$nit')");
        $insertSQL -> execute();
        echo '<script> alert("REGISTRO EXITOSO");</script>';
        echo '<script>window.location="login.html"</script>';
        
    } 
}
    ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuario</title>
    <link href="assets/img/log.png" rel="icon">
  <link href="assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="assets/css/registroo.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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
            $("#documento").on("input", function() {
                validateField(/^\d{8,10}$/, this, "Debe ingresar solo números (8 a 10 dígitos)");
            });

            $("#nombre").on("input", function() {
                validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, this, "Ingrese un nombre válido (solo letras)");
            });

            $("#apellido").on("input", function() {
                validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, this, "Ingrese un apellido válido (solo letras)");
            });

            $("#telefono").on("input", function() {
                validateField(/^\d{10}$/, this, "Debe ingresar solo números (10 dígitos)");
            });

            $("#correo").on("input", function() {
                validateField(/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/, this, "Ingrese un correo válido que lleve '@'");
            });

            $("#direccion").on("input", function() {
                validateField(/^[a-zA-Z0-9#.,\-_áéíóúÁÉÍÓÚñÑ\s]*$/, this, "Ingrese una dirección válida");
            });

            $("#password").on("input", function() {
                validateField(/^[a-zA-Z0-9]{8}$/, this, "Debe ingresar solo números y letras (8 caracteres)");
            });
        });

        function validateForm() {
            const isDocumentoValid = validateField(/^\d{8,10}$/, document.getElementById("documento"), "Debe ingresar solo números (8 a 10 dígitos)");
            const isNombreValid = validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, document.getElementById("nombre"), "Debe ingresar solo letras");
            const isApellidoValid = validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, document.getElementById("apellido"), "Debe ingresar solo letras");
            const isTelefonoValid = validateField(/^\d{10}$/, document.getElementById("telefono"), "Debe ingresar solo números (10 dígitos)");
            const isCorreoValid = validateField(/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/, document.getElementById("correo"), "Debe ser un correo válido que lleve '@'");
            const isDireccionValid = validateField(/^[a-zA-Z0-9#.,\-_áéíóúÁÉÍÓÚñÑ\s]*$/, document.getElementById("direccion"), "Debe ser una dirección válida");
            const isPasswordValid = validateField(/^[a-zA-Z0-9]{8}$/, document.getElementById("password"), "Debe ingresar solo números y letras (8 caracteres)");

            return isDocumentoValid && isNombreValid && isApellidoValid && isTelefonoValid && isCorreoValid && isDireccionValid && isPasswordValid;
        }
    </script>
</head>
</head>
<body>

<div class="regresar">
    <button onclick="goBack()" class="return">
        <span class="btxt">Regresar</span><i class="animate"></i>
    </button>
        
</div>

    <div class="login-box">
        <img src="assets/img/log.farma.png">
        <h1>REGISTRO USUARIO</h1>

        <form method="post" name="form1" id="form1"  autocomplete="off" onsubmit="return validateForm()"> 

        <div class="row">
        <select name="id_doc">
                <option value ="">Seleccione el Tipo de Documento</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from t_documento ORDER BY tipo ASC");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_doc'] . ">"
                     . $fila['tipo'] . "</option>";
                } 
                ?>
            </select>
        
            <input type="text" name="documento" id="documento" placeholder="Digite su Documento" required>
            </div>

            <div class="row">
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese su Nombre" required>
            <input type="text" name="apellido" id="apellido" placeholder="Ingrese su Apellido">
            </div>

            <div class="row">
            <select name="nit">
                <option value ="">Seleccione el Tipo de EPS</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from empresas ORDER BY empresa ASC");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['nit'] . ">"
                     . $fila['empresa'] . "</option>";
                } 
                ?>
            </select>

            <select name="id_rh">
                <option value ="">Seleccione el Tipo de Sangre</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from rh ORDER BY rh ASC");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_rh'] . ">"
                     . $fila['rh'] . "</option>";
                } 
                ?>
            </select></div>

            <div class="row">
            <input type="text" name="telefono" id="telefono" placeholder="Ingrese su Telefono">
            <input type="text" name="correo" id="correo" placeholder="Ingrese su Correo">
            </div>

            <div class="row">
            <select name="id_departamento" id="id_depart">
                <option value="">Seleccione el Departamento</option>
                <?php
                    $control = $con->prepare("SELECT * FROM departamentos ORDER BY depart ASC");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $fila['id_depart'] . "'>" . $fila['depart'] . "</option>";
                    }
                ?>
            </select>
            <select name="id_municipio" id="id_municipio">
                <option value="">Seleccione el Municipio</option>
            </select>
        </div>

            <div class="row">
            <input type="text" name="direccion" id="direccion" placeholder="Ingrese la dirección">   
            
             <input type="password" name="password" id="password" placeholder="Ingrese la Contraseña">

            </div>
             <br><br>

            
             <input type="submit" name="validar"  value="Registrarme">
            <input type="hidden" name="MM_insert" value="formreg">
            </form>
    </div>

    <script>
    function goBack() {
        window.location.href = 'login.html';
    }

    $(document).ready(function(){
        $('#id_depart').change(function(){
            var id_depart = $(this).val();
            $.ajax({
                type: "POST",
                url: "municipio.php",
                data: {id_depart: id_depart},
                success: function(response){
                    $('#id_municipio').html(response);
                }
            });
        });
    });

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