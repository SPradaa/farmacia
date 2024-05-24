<?php
// session_start();
    require_once("../../db/connection.php"); 
    $db = new Database();
    $con = $db->conectar();
	

	
require_once("../../controller/seg.php");
validarSesion();


if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
   $documento= $_POST['documento'];
   $fecha= $_POST['fecha'];
   $id_hor= $_POST['id_hor'];
   $id_esp= $_POST['id_esp'];
   $docu_medico= $_POST['docu_medico'];

   $sql= $con -> prepare ("SELECT * FROM citas WHERE documento='$documento'");
   $sql -> execute();
   $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

   if ($fila){
      echo '<script>alert ("EL DOCUMENTO YA EXISTE //CAMBIELO//");</script>';
      echo '<script>window.location="citas.php"</script>';
   }
   else

  if ($documento=="" || $fecha=="" || $id_hor=="" || $id_esp=="" || $docu_medico=="")
   {
      echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
      echo '<script>window.location="registro.php"</script>';
   }
   else
   {
     $insertSQL = $con->prepare("INSERT INTO citas(documento, fecha, id_hor, id_esp, docu_medico) VALUES('$documento', '$fecha', '$id_hor', '$id_esp',  '$docu_medico')");
     $insertSQL -> execute();
     echo '<script> alert("REGISTRO EXITOSO");</script>';
     echo '<script>window.location="citas.php"</script>';
     
 } 
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link rel="stylesheet" href="css/agendarcita.css">
</head>
<body>

    <div class="regresar">
        <button onclick="goBack()" class="return">
            <span class="btxt">Regresar</span><i class="animate"></i>
        </button>
            
    </div>

    <div class="login-box">
        <h2></h2>
        <h3>Agendar Cita</h3>
        <form action="" method="post">
            <div class="row">
                <label for="documento">Documento:</label><br>
                <input type="text" class="form-control" id="documento" name="documento" required>
            </div>
            <div class="row">
                <label for="fecha">Fecha:</label><br>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="row">
                <label for="id_esp">Hora:</label><br>
                <select class="form-control" id="id_hor" name="id_hor" required><br>
            <option value=""></option>

            <?php
            $control = $con->prepare("SELECT * from horarios");
            $control->execute();
            while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value=" . $fila['id_hor'] . ">"
                    . $fila['horario'] . "</option>"; 
            }
            ?>
            
            

        </select>
        <br>
        <br>

            <div class="row">
                <label for="id_esp">Especialización:</label><br>
                <select class="form-control" id="id_esp" name="id_esp" required>
            <option value=""></option>

            <?php
            $control = $con->prepare("SELECT * from especializacion");
            $control->execute();
            while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value=" . $fila['id_esp'] . ">"
                    . $fila['especializacion'] . "</option>";
            }
            ?>

        </select>
        <br>
        <br>

        <div class="row">
                <label for="docu_medico">Seleccione Médico:</label><br>
                <select class="form-control" id="docu_medico" name="docu_medico" required>
            <option value=""></option>

            <?php
            $control = $con->prepare("SELECT * from medicos");
            $control->execute();
            while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value=" . $fila['docu_medico'] . ">"
                    . $fila['nombre_comple'] . "</option>";
            }
            ?>

        </select>
            </div>
            <!-- Los otros campos del formulario -->
            <input  class="btn btn-primary" type="submit" name="validar" value="Consultar">
            <input type="hidden" name="MM_insert" value="formreg">

        </form>
    </div> 
    <script>
        function goBack() {
            window.location.href = 'citas.php';
        }
    </script>
</body>
</html>


