<?php
// session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();


require_once("../../../../controller/seg.php");
validarSesion();


$id_cita = isset($_GET['id_cita']) ? $_GET['id_cita'] : null;

$sql = "SELECT * FROM autorizaciones";
if ($id_cita) {
    $sql .= " WHERE id_cita = :id_cita";
}

$stmt = $con->prepare($sql);

if ($id_cita) {
    $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
}

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    foreach ($results as $row) {
        $codigo_autorizacion = $row["cod_auto"];
        $id_cita = $row["id_cita"];
        $fecha = $row["fecha"];
        $documento = $row["documento"];
        $docu_medico = $row["docu_medico"];
        $medicamentos = json_decode($row["medicamento"], true); 
        $fecha_venc = $row["fecha_venc"];
        $id_estado = $row["id_estado"];

        // Imprimir cada fila
       
    }
} else {
    echo "0 resultados";
}

$usuario = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$usuario->bindParam(':documento', $documento, PDO::PARAM_INT);
$usuario->execute();
$filaone = $usuario->fetch(PDO::FETCH_ASSOC);

$docu = $con->prepare("SELECT * FROM t_documento WHERE id_doc = :t_doc");
$docu->bindParam(':t_doc', $filaone['id_doc'], PDO::PARAM_INT);
$docu->execute();
$filacero = $docu->fetch(PDO::FETCH_ASSOC);

$med = $con->prepare("SELECT * FROM medicos WHERE docu_medico = :docu_medico");
$med->bindParam(':docu_medico', $docu_medico, PDO::PARAM_INT);
$med->execute();
$filatwo = $med->fetch(PDO::FETCH_ASSOC);

$especializacion = $con->prepare("SELECT * FROM especializacion WHERE id_esp = :especializacion");
$especializacion->bindParam(':especializacion', $filatwo['id_esp'], PDO::PARAM_INT);
$especializacion->execute();
$filathree = $especializacion->fetch(PDO::FETCH_ASSOC);

function generarTablaMedicamentos($medicamentos) {
    $html = '<table>';
    $html .= '<tr><th>Nombre</th><th>Presentación</th><th>Cantidad</th></tr>';
    foreach ($medicamentos as $med) {
        $html .= '<tr>';
 
        $html .= '<td>' . $med['name'] . '</td>';
        $html .= '<td>' . ($med['presentacion'] ?: 'N/A') . '</td>';
        $html .= '<td>' . $med['cantidad'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AUTORIZACION</title>
    <link rel="stylesheet" href="../../css/estilo.css">
    <link rel="stylesheet" href="css/ver_autorizacion.css">
</head>
<body>
    <div class="contenedor">
        <img src="../../../../assets/img/log.farma.png" alt="Logo" class="logo">
        <br><br><br><br><br>
        <h2>AUTORIZACIÓN</h2>
      
        <div class="seccion">
            <h3>Fecha Autorización</h3>
            <table class="datos">
                <tr>
                    <th>Fecha</th>
                    <td><?php echo $fecha; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Paciente</h3>
            <table class="datos">
                <tr>
                    <th>Tipo de Documento</th>
                    <td><?php echo $filacero['tipo']; ?></td>
                </tr>
                <tr>
                    <th>Documento</th>
                    <td><?php echo $documento; ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $filaone['nombre']; ?></td>
                </tr>
                <tr>
                    <th>Apellido</th>
                    <td><?php echo $filaone['apellido']; ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo $filaone['telefono']; ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo $filaone['correo']; ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><?php echo $filaone['direccion']; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos de la Autorización</h3>
            <table class="datos">
                <tr>
                    <th>Código de la Autorización</th>
                    <td><?php echo $codigo_autorizacion; ?></td>
                </tr>
                <tr>
                    <th>Medicamento</th>
                    <td><?php echo generarTablaMedicamentos($medicamentos); ?></td>
                </tr>
               
                <tr>
                    <th>Fecha de Vencimiento</th>
                    <td><?php echo $fecha_venc; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Médico</h3>
            <table class="datos">
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $filatwo['nombre_comple']; ?></td>
                </tr>
                <tr>
                    <th>Especialización</th>
                    <td><?php echo $filathree['especializacion']; ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo $filatwo['telefono']; ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo $filatwo['correo']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

