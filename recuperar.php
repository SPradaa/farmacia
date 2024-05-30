<?php

   require_once ("db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
   session_start();

  
   if (isset($_POST['recuperar']))
   {

     $correo=$_POST['correo'];

    $sql= $con -> prepare ("SELECT * FROM usuarios WHERE correo='$correo'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

   $digitos ="sakur02ue859y2u389rhdewirh102385y1285013289";
   $longitud= 4;
   $codigo= substr(str_shuffle($digitos), 0, $longitud);

    $insert= $con -> prepare ("UPDATE usuarios SET token='$codigo' Where correo='$correo'");
    $insert -> execute();
    $fila1 = $insert -> fetchAll(PDO::FETCH_ASSOC);

   //codigo de envio
   $paracorreo = "$correo";
   $titulo ="Recuperar Contraseña";
   $msj = "Su codigo de verificacion es: '$codigo'";
   
   $tucorreo="From:colaya741@gmail.com";
   if(mail($paracorreo, $titulo, $msj, $tucorreo))
   {
     echo '<script> alert ("Su codigo ha sido enviado al correo anteriormente digitado");</script>';
     echo '<script>window.location="code.php"</script>';
   }
   else{
       echo "Error";
   }

 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer Contraseña</title>
  <link href="assets/img/logof.png" rel="icon">
  <link href="assets/img/logof.png" rel="apple-touch-icon">
  <link rel="stylesheet" href="assets/css/recuperar.css">
</head>
<body>
<div class="regresar">
    <button onclick="goBack()" class="return">
        <span class="btxt">Regresar</span><i class="animate"></i>
    </button>   
</div>

  <div class="container">
    <h2>¿Olvidaste tu contraseña?</h2>
    <form method="post" name="form1" id="form1"  autocomplete="on"> 
      <div class="input-group">
        <label for="email">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" pattern="[0-9A-Za-z@._]{5,60}" title="El correo debe ser alfanúmerico y tener caracteres especiales" required>
      </div>
      <button type="submit" name="recuperar" class="btn btn-primary">Restablecer</button>
</form>
  </div>

  <script src="script.js"></script>

  <script>
        function goBack() {
            window.location.href = 'login.html';
        }
    </script>
</body>
</html>
