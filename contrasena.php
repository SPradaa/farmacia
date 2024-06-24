<?php

   require_once ("db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
   session_start();

   if (isset($_POST['actualizar'])){ 
        $documento= $_SESSION['user_id'];
        $contrasena= $_POST['contrasena']; 
        $confirmar_contrasena= $_POST['confirmar_contrasena'];
        
        if($contrasena == $confirmar_contrasena) {
            $sql= $con -> prepare ("SELECT * FROM usuarios");
            $sql -> execute();
            $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
            
            if ($contrasena=="" || $confirmar_contrasena=="")
                {
                echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
                echo '<script>window.location="registro_empleados.php"</script>';
                }
        
                else{
                $consulta = $con->prepare("SELECT * FROM usuarios WHERE documento = '$documento'");
                $consulta -> execute();
                $consul = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        
                
        
        
                $pass_cifrado = password_hash($contrasena,PASSWORD_DEFAULT, array("pass"=>12));
        
                $insertSQL = $con->prepare("UPDATE usuarios SET password='$pass_cifrado' WHERE documento = '$documento' ");
                $insertSQL -> execute();
                echo '<script> alert("CONTRASEÑA ACTUALIZADA CORRECTAMENTE");</script>';
                echo '<script>window.location="login.html"</script>';
                unset($_SESSION['user_id']);
            }  
        }
        else {
            echo '<script>alert ("LAS CONTRASEÑAS NO COINCIDEN");</script>';
        }
        
    }
  
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulario de Cambio de Contraseña</title>
<link href="assets/img/log.png" rel="icon">
<link href="assets/img/log.png" rel="apple-touch-icon">
<link rel="stylesheet" href="assets/css/contrasenaa.css">
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
          $("#contrasena").on("input", function() {
                validateField(/^[a-zA-Z0-9]{8}$/, this, "Debe ingresar solo números y letras (8 caracteres)");
            });
          });

        function validateForm() {
          const isContrasenaValid = validateField(/^[a-zA-Z0-9]{8}$/, document.getElementById("contrasena"), "Debe ingresar solo números y letras (8 caracteres)");
          return isContrasenaValid;
        }
    </script>
</head>
<body>
<div class="regresar">
    <button onclick="goBack()" class="return">
        <span class="btxt">Regresar</span><i class="animate"></i>
    </button>
        
</div>

<div class="container">
  <h2>Cambio de Contraseña</h2>
  <form method="post" name="form1" id="form1"  autocomplete="on"> 
    <div class="form-group">
      <label for="contrasena">Nueva Contraseña:</label>
      <input type="password" id="contrasena" name="contrasena" pattern="[0-9A-Za-z]{4,15}" title="La contraseña debe tener de 4 a 15 caracteres(números o letras).No se permiten caracteres especiales." required>
    </div>

    <div class="form-group">
      <label for="confirmar_contrasena">Confirmar Contraseña:</label>
      <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
    </div>
    
    <input type="submit" name="actualizar" value="Actualizar">
      <input type="hidden" name="MM_insert" value="formreg">
    
    <div id="mensaje_error" class="mensaje-error"></div>
  </form>
</div>
<script>
document.querySelector("form").onsubmit = function() {
  var nuevaContraseña = document.getElementById("nueva_contraseña").value;
  var confirmarContraseña = document.getElementById("confirmar_contraseña").value;
  if (nuevaContraseña !== confirmarContraseña) {
    document.getElementById("mensaje_error").innerHTML = "Las contraseñas no coinciden";
    return false; // Detiene el envío del formulario si las contraseñas no coinciden
  }
};
</script>

<script>
        function goBack() {
            window.location.href = 'login.html';
        }
    </script>
</body>
</html>
