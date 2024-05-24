<?php

   require_once ("../../../../db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
   session_start();
?>

<?php

   if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
   {
      $nit= $_POST['nit'];
      $empresa= $_POST['noempresa'];
      $code= $_POST['code'];
      $licencia= $_POST['licencia'];
      $inicio= $_POST['inicio'];
      $fin= $_POST['fin'];
      $estado= $_POST['estados']; 
      

      $sql= $con -> prepare ("SELECT * FROM empresas WHERE nit='$nit'");
      $sql -> execute();
      $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
 
      if ($fila){
         echo '<script>alert ("El NIT DE LA EMPRESA YA EXISTE //CAMBIELO//");</script>';
         echo '<script>window.location="create_emp.php"</script>';
      }
      else
   
     if ($nit=="" || $empresa=="" || $code=="" )
      {
         echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
         echo '<script>window.location="create_emp.php"</script>';
      }
      else
      {
        // $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
        $insertSQL = $con->prepare("INSERT INTO empresas(nit, empresa, licencia, inicio, fin, codigo_unico, id_estado) VALUES('$nit', '$empresa', '$licencia', '$inicio', '$fin', '$code', '$estado')");
        $insertSQL -> execute();
        echo '<script> alert("REGISTRO EXITOSO");</script>';
        echo '<script>window.location="index_emp.php"</script>';
        
    } 
}
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuario</title>
    <link rel="stylesheet" href="../../css/registroe.css"> 
</head>
<body>

        
            <div class="col-md-6">
                <form action="index_emp.php">
                    <input type="submit" value="Regresar" class="btn btn-secondary"/>
                </form>
            </div>



    <div class="login-box">
        <h1>Crear empresas</h1>
        <p>Ingrese los siguientes datos:</p>

        <form method="post" name="form1" id="form1"  autocomplete="off"> 
        
            <label for="documento">Nit empresa</label>
            <input type="number" name="nit" id="nit" pattern="[0-9]{8,11}" placeholder="Digite el nit de la empresa" title="El documento debe tener solo números de 8 a 10 dígitos">
<br>
<br>
            <label for="documento">Nombre empresa</label>
            <input type="text" name="noempresa" id="empresa"   placeholder="Ingrese el nombre de la empresa" title="El documento debe tener solo números de 8 a 10 dígitos">
<br>
<br>
<label for="documento">Licencia</label>
            <input type="text" name="licencia" id="licencia" value="" readonly>
<br>

<input type="button" onclick="generate()"  value="Generar Licencia"></button>

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
            <label for="nombre">codigo unico </label>
            <input type="number" name="code" id="code" pattern="[0-9 ]{3}" placeholder="Ingrese el codigo único de la empresa" title="El codigo debe tener solo 3 numeros">

<br>
<br>
<select name="estados">
                <option value ="">Seleccione el estado de la empresa </option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from estados WHERE id_estado IN (3, 4)");
                    $control -> execute();
                    $id1 = 3;  
$id2 = 4; 
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_estado'] . ">"
                     . $fila['estado'] . "</option>";
                } 
                ?>
            </select>

            <br>
            <br>
          

            <input type="submit" name="validar" value="Registrar Empresa">
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