<?php
// session_start();
    require_once("../../../../db/connection.php"); 
    $db = new Database();
    $con = $db->conectar();
	echo"session start" ;
?>

<?php
	ini_set('session.gc_maxlifetime', 600);
	session_set_cookie_params(600);
	
require_once("../../../../controller/seguridad.php");
validarSesion();

// Verifica si la variable de sesión está seteada
if (isset($_SESSION['id_especializacion'])) {
    // Recupera el valor de la variable de sesión
    $id_especializacion = $_SESSION['id_especializacion'];

    // Imprime el valor de la variable
    echo "ID de la especialización seleccionada: " . $id_especializacion;
} else {
    // Redirige o realiza alguna acción en caso de que la variable de sesión no esté seteada
    echo "La variable de sesión no está seteada.";
}
?>
<?php 

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formreg")) {
    $documento = $_POST['documento'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hour'];
    $medico = $_POST['id_medico'];
    $estado = $_POST['id_estado'];

    // Verificar si ya existe una cita para el mismo día y la misma hora
    $citaExistente = $con->prepare("SELECT * FROM citas WHERE fecha='$fecha' AND hora='$hora'");
    $citaExistente->execute();
    $citaExistenteResultado = $citaExistente->fetchAll(PDO::FETCH_ASSOC);

    // Verificar que todos los campos estén llenos
    if ($fecha == "" || $hora == "" || $medico == "" || $estado == "") {
        echo '<script>alert("Existen campos vacíos en la cita.");</script>';
        echo '<script>window.location="citas.php"</script>';
    } elseif ($citaExistenteResultado) {
        echo '<script>alert("Ya hay una cita programada para la misma fecha y hora.");</script>';
        echo '<script>window.location="citas.php"</script>';
    } else {
        // Aquí debes adaptar la lógica para insertar en la tabla de citas
        // Utiliza los valores $documento, $fecha, $hora, $medico, $estado en la inserción
        $insertCita = $con->prepare("INSERT INTO citas(documento, fecha, hora, docu_medico, id_esp, id_estado) VALUES('$documento', '$fecha', '$hora', '$medico', '$id_esp', '$estado')");
        $insertCita->execute();

        echo '<script>alert("CITA REGISTRADA EXITOSAMENTE");</script>';
        echo '<script>window.location="indexadm.php"</script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <script src="../script/hour.js"></script>
</head>

<body>
    <h2>agenda de citas</h2>

    <form method="post" name="form1" id="form1" autocomplete="off">
        <label for="documento"> Documento </label>
        <input type="number" name="documento" pattern="[0-9]{8,11}" placeholder="Digite su Documento"
            title="El documento debe tener solo números de 8 a 10 dígitos">
        <label for="fecha">Ingrese la fecha de su cita</label>
        <input type="date" name="fecha">
        <label for="fecha">Ingrese la hora </label>
        <input type="time" name="hour" id="hour">

       
        <select name="id_medico">
            <?php
            // Verifica si la variable de sesión está seteada
            if (isset($_SESSION['id_especializacion'])) {
                // Recupera el valor de la variable de sesión
                $id_especializacion = $_SESSION['id_especializacion'];

                // Consulta para obtener los médicos de la especialización seleccionada
                $control = $con->prepare("SELECT * FROM medicos WHERE id_esp = :id_esp");
                $control->bindParam(':id_esp', $id_especializacion, PDO::PARAM_INT);
                $control->execute();

                // Imprime las opciones del menú desplegable
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=" . $fila['docu_medico'] . ">" . $fila['nombre_comple'] . "</option>";
                }
            } else {
                echo "La variable de sesión no está seteada.";
            }
            ?>
        </select>

        <select name="id_estado" readonly >
                <!-- <option value="">Seleccione  un estado</option> -->

                <?php
                $control = $con->prepare("SELECT * FROM estados WHERE id_estado IN (3)");
                $control->execute();

                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=" . $fila['id_estado'] . ">"
                        . $fila['estado'] . "</option>";
                }
                ?>
                <input type="submit" name="validar" value="Agendar Mi Cita">
        <input type="hidden" name="MM_insert" value="formreg">
            </select>

    </form>
   
</body>

</html>
