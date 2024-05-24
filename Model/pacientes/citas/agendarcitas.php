<?php
// session_start();
    require_once("../../../db/connection.php"); 
    $db = new Database();
    $con = $db->conectar();
	

	
require_once("../../../controller/seg.php");
validarSesion();


if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
   $documento= $_POST['documento'];
   $fecha= $_POST['fecha'];
   $hora= $_POST['hora'];
   $id_esp= $_POST['id_esp'];
   $docu_medico= $_POST['docu_medico'];

   $citaExistente = $con->prepare("SELECT * FROM citas WHERE fecha='$fecha' AND hora='$hora' AND docu_medico='$docu_medico'");
   $citaExistente->execute();
   $citaExistenteResultado = $citaExistente->fetchAll(PDO::FETCH_ASSOC);


  if ($documento=="" || $fecha=="" || $hora=="" || $id_esp=="" || $docu_medico=="")
   {
      echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
      echo '<script>window.location="agendarcitas.php"</script>';

   } elseif ($citaExistenteResultado) {
    echo '<script>alert("Ya hay una cita programada para la misma fecha y hora con el medico seleccionado.");</script>';
    echo '<script>window.location="agendarcitas.php"</script>';
   
    } else {

     $insertSQL = $con->prepare("INSERT INTO citas(documento, fecha, hora, id_esp, docu_medico) VALUES('$documento', '$fecha', '$hora', '$id_esp',  '$docu_medico')");
     $insertSQL -> execute();
     echo '<script> alert("REGISTRO EXITOSO");</script>';
     echo '<script>window.location="agendarcitas.php"</script>';
     
 } 
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <link rel="stylesheet" href="../css/agendarcita.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#id_esp').change(function(){
                var especialidad_id = $(this).val();
                $.ajax({
                    url: 'getmedicos.php',
                    method: 'POST',
                    data: {id_esp: especialidad_id},
                    dataType: 'json',
                    success: function(data) {
                        $('#docu_medico').empty();
                        $('#docu_medico').append($('<option>', {
                            value: '',
                            text: ''
                        }));
                        $.each(data, function(key, value) {
                            $('#docu_medico').append($('<option>', {
                                value: value.docu_medico,
                                text: value.nombre_comple
                            }));
                        });
                    }
                });
            });
        });
    </script>
    <!-- Agrega este script en el head -->
</head>
<body>

    <div class="regresar">
        <button onclick="goBack()" class="return">
            <span class="btxt">Regresar</span><i class="animate"></i>
        </button>
            
    </div>

    <div class="login-box">
        <img src="../../../assets/img/log.farma.png">
        <h2>¡BIENVENIDO A VITALFARMA!</h2>
        <P>Agenda tu cita</P>
        <br>
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
                <label for="hora">Hora:</label><br>
                <input type="time" class="form-control" id="hora" name="hora" required>
            </div>

            
        </select>
        
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
                <select class="form-control" id="docu_medico" name="docu_medico" required onchange="verificarMedico()">
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


