<?php
// session_start();
require_once("../../../db/connection.php"); 
require_once("../../../controller/seg.php");

validarSesion();
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formreg"))
{
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $id_esp = $_POST['id_esp'];
    $docu_medico = $_POST['docu_medico'];

    if ($fecha == "" || $hora == "" || $id_esp == "" || $docu_medico == "")
    {
        echo '<script>alert("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="agendarcitas.php"</script>';
    } else {
        // Convertir la hora seleccionada a un objeto DateTime para poder manipularlo
        $horaInicio = new DateTime($hora);
        // Clonar la hora de inicio y agregar 20 minutos para obtener la hora de fin
        $horaFin = clone $horaInicio;
        $horaFin->add(new DateInterval('PT20M'));
        // Formatear las horas para que sean comparables con la base de datos
        $horaInicioStr = $horaInicio->format('H:i:s');
        $horaFinStr = $horaFin->format('H:i:s');

        // Verificar si hay citas que se topen con la hora seleccionada
        $citaExistente = $con->prepare(
            "SELECT * FROM citas 
            WHERE fecha = :fecha 
            AND docu_medico = :docu_medico 
            AND (
                (hora < :horaFin AND ADDTIME(hora, '00:20:00') > :horaInicio)
            )"
        );
        $citaExistente->execute([
            ':fecha' => $fecha,
            ':horaInicio' => $horaInicioStr,
            ':horaFin' => $horaFinStr,
            ':docu_medico' => $docu_medico
        ]);
        $citaExistenteResultado = $citaExistente->fetchAll(PDO::FETCH_ASSOC);

        if ($citaExistenteResultado) {
            echo '<script>alert("Ya hay una cita programada con la hora seleccionada.");</script>';
            echo '<script>window.location="agendarcitas.php"</script>';
        } else {
            $insertSQL = $con->prepare("INSERT INTO citas(documento, fecha, hora, id_esp, docu_medico) VALUES(:documento, :fecha, :hora, :id_esp, :docu_medico, )");
            $insertSQL->execute([
                ':documento' => $documento,
                ':fecha' => $fecha,
                ':hora' => $hora,
                ':id_esp' => $id_esp,
                ':docu_medico' => $docu_medico
            ]);
            echo '<script>alert("REGISTRO EXITOSO");</script>';
            echo '<script>window.location="agendarcitas.php"</script>';
        }
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
                <label for="fecha">Fecha:</label><br>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="row">
                <label for="hora">Hora:</label><br>
                <input type="time" class="form-control" id="hora" name="hora" required>
            </div>
            <br>
            <div class="row">
                <label for="id_esp">Especialización:</label><br>
                <select class="form-control" id="id_esp" name="id_esp" required>
                    <option value=""></option>
                    <?php
                    $control = $con->prepare("SELECT * FROM especializacion");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=" . $fila['id_esp'] . ">" . $fila['especializacion'] . "</option>";
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
                        $control = $con->prepare("SELECT * FROM medicos");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['docu_medico'] . ">" . $fila['nombre_comple'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <input class="btn btn-primary" type="submit" name="validar" value="Consultar">
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
