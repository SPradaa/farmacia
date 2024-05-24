<?php
    session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();

    $sql = $con -> prepare ("SELECT * FROM empresas, estados
     WHERE empresas.id_estado = estados.id_estado AND empresas.nit= '".$_GET['id']."'");
    $sql -> execute();
    $usua =$sql -> fetch();
?>

<?php

if(isset($_POST["update"]))
 {
    $licencia= $_POST['licencia'];
    $inicio= $_POST['inicio'];
    $fin= $_POST['fin'];
    // $codigo_unico= $_POST['codigo_unico'];
 
   if ($licencia=="" || $inicio=="" || $fin=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="index_emp.php"</script>';
    }
    else
    {
      $insertSQL = $con->prepare("UPDATE empresas SET licencia = '$licencia', 
      inicio = '$inicio', fin = '$fin'
      WHERE nit = '".$_GET['id']."'");
      $insertSQL -> execute();
      echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';
      echo '<script>window.location="index_emp.php"</script>';
      
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
    <title>Actualizar Empresa</title>

</head>
<body>
    <div class="formulario">
        <h1>Editar Empresa</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="campos">
                <input type="text" name="nit"  title="El nit debe tener solo numeros (10 digitos)" value="<?php echo $usua['nit']?>" disabled>
                <input type="text" name="empresa" pattern="[a-zA-Z ]{4,30}" title="La empresa debe tener solo letras" value="<?php echo $usua['empresa']?>"  disabled>
        
           
            </div>
            <div class="campos">
            
            <input type="text" name="licencia" id="licencia" value="" placeholder="genere una nueva licencia"> <input type="button"    onclick="generate()"  value="Nueva Licencia" class="generate" ></button>
         
            </div>

            <div class="campos">
            <label for="nombre">Fecha de inicio de la licencia </label>
            <input type="date" name="inicio" id="nombre" placeholder="Ingrese la fecha de inicio de la licencia" value="<?php echo date('Y-m-d'); ?>">  
            </div>
            <div class="campos">
            <label for="nombre">Fecha de expiracion de la licencia </label>
            <?php
$fechaInicio = date('Y-m-d'); // Obtener la fecha de inicio actual
$fechaFin = date('Y-m-d', strtotime('+1 year', strtotime($fechaInicio))); // Sumar un año a la fecha de inicio

echo '<input type="date" name="fin" id="nombre" value="' . $fechaFin . '">';
?>
            </div>
            <br><br>
            <input type="submit" name="update" value="Actualizar">
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
</body>
</html>
