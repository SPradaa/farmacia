<?php

   require_once ("../../../../db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
   session_start();

?>

<?php

   if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
   {
      $identificador= $_POST['identificador'];
      $licencia= $_POST['licencia'];
      $inicio= $_POST['inicio'];
      $fin= $_POST['fin'];
      $estado= $_POST['estado']; 
      

      $sql= $con -> prepare ("SELECT * FROM licencias WHERE licencia='$licencia'");
      $sql -> execute();
      $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
 
      if ($fila){
         echo '<script>alert ("la licencia YA EXISTE //CAMBIELO//");</script>';
         echo '<script>window.location="create_li.php"</script>';
      }
      else
   
     if ($licencia=="" || $estado==""  )
      {
         echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
         echo '<script>window.location="create_li.php"</script>';
      }
      else
      {
        // $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
        $insertSQL = $con->prepare("INSERT INTO licencias(id_licencia, licencia, f_inicio, f_fin, id_estado) VALUES('$identificador', '$licencia', '$inicio', '$fin','$estado')");
        $insertSQL -> execute();
        echo '<script> alert("REGISTRO EXITOSO");</script>';
        echo '<script>window.location="index_li.php"</script>';
        
    } 
}
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuario</title>
    <link rel="stylesheet" href="../../css/licencia.css"> 
</head>
<body>

    <div class="login-box">
        <h1>Crear Licencia</h1>
        <form method="post" name="form1" id="form1"  autocomplete="off"> 
        
            <label for="documento">id licencia</label>
            <input type="text" name="identificador" id="documento" pattern="[0-9]{3}" placeholder="id" title="El documento debe tener solo números de 8 a 10 dígitos" readonly>
<br>
<br>
            <label for="documento">Licencia</label>
            <input type="text" name="licencia" id="licencia" value="" readonly>
<br>
<br>
            <button onclick="generate()">Generar Licencia</button>

<!-- Prueba JavaScript -->

             <!-- fin prueba -->

<br>
<br>
            <label for="nombre">Fecha de inicio de la licencia </label>
            <input type="date" name="inicio" id="nombre" placeholder="Ingrese la fecha de inicio de la licencia" value="<?php echo date('Y-m-d'); ?>">
<br>
<br>

            <label for="nombre">Fecha de fin de la licencia </label>
            <?php
$fechaInicio = date('Y-m-d'); // Obtener la fecha de inicio actual
$fechaFin = date('Y-m-d', strtotime('+1 year', strtotime($fechaInicio))); // Sumar un año a la fecha de inicio

echo '<input type="date" name="fin" id="nombre" value="' . $fechaFin . '">';
?>

<br>
<br>
    <select name="estado">
    <option value="">Seleccione  un estado</option>

    <?php
    $control = $con->prepare("SELECT * FROM estados WHERE id_estado IN (3, 4)");
    $control->execute();

    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value=" . $fila['id_estado'] . ">"
            . $fila['estado'] . "</option>";
    }
    ?>
</select>

            <input type="submit" name="validar" value="Crear">
            <input type="hidden" name="MM_insert" value="formreg">

      
           
          
            </form>
            
    </div>
    <script>
    function generate() {
        var caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";
        var longitud = 10;
        var nuevaLicencia = Array.from({ length: longitud }, () => caracteres.charAt(Math.floor(Math.random() * caracteres.length))).join('');
        
        // Mostrar la variable en el valor del campo de entrada
        document.getElementById("licencia").value = nuevaLicencia;

    
    }
</script>
<script>
        function goBack() {
            window.location.href = 'inadm.php';
        }
    </script>
</body>
</html>