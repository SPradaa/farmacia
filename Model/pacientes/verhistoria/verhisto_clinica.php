<?php
session_start();
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Verificar si el id_cita se proporciona en la URL
if(isset($_GET['id_cita'])) {
    $id_cita = $_GET['id_cita'];
} else {
    // Manejar el caso en el que no se proporciona un id_cita
    echo "ID de cita no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico</title>
    <link rel="stylesheet" href="../css/verhisto.css">
</head>
<body>
    <div class="contenedor">
        <img src="../../../assets/img/log.farma.png" alt="Logo" class="logo">
        <br><br><br><br><br>
        <h2>Historial Clínico</h2>
        <?php
        if ($con) {
            $consulta = $con->prepare("SELECT histo_clinica.*, 
                                              usuarios.documento AS doc_usuario, usuarios.nombre AS nombre_usuario, usuarios.apellido AS apellido_usuario, usuarios.telefono AS telefono_usuario, usuarios.correo AS correo_usuario, usuarios.direccion AS direccion_usuario,
                                              medicos.nombre_comple AS nombre_medico, medicos.telefono AS telefono_medico, medicos.correo AS correo_medico, 
                                              t_documento.tipo AS tipo_doc,
                                              especializacion.especializacion
                                       FROM histo_clinica 
                                       JOIN usuarios ON histo_clinica.documento = usuarios.documento 
                                       JOIN medicos ON histo_clinica.docu_medico = medicos.docu_medico
                                       JOIN t_documento ON usuarios.id_doc = t_documento.id_doc 
                                       JOIN especializacion ON medicos.id_esp = especializacion.id_esp
                                       WHERE histo_clinica.id_cita = :id_cita");
            $consulta->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
            $consulta->execute();
            if ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="seccion">
            <h3>Datos del Paciente</h3>
            <table class="datos">
                <tr>
                    <th>Tipo de Documento</th>
                    <td><?php echo htmlspecialchars($fila['tipo_doc']); ?></td>
                </tr>
                <tr>
                    <th>Documento</th>
                    <td><?php echo htmlspecialchars($fila['doc_usuario']); ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo htmlspecialchars($fila['nombre_usuario']); ?></td>
                </tr>
                <tr>
                    <th>Apellido</th>
                    <td><?php echo htmlspecialchars($fila['apellido_usuario']); ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo htmlspecialchars($fila['telefono_usuario']); ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo htmlspecialchars($fila['correo_usuario']); ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><?php echo htmlspecialchars($fila['direccion_usuario']); ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos de la Historia Clínica</h3>
            <table class="datos">
                <tr>
                    <th>Descripción</th>
                    <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                </tr>
                <tr>
                    <th>Diagnóstico</th>
                    <td><?php echo htmlspecialchars($fila['diagnostico']); ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Médico</h3>
            <table class="datos">
                <tr>
                    <th>Nombre</th>
                    <td><?php echo htmlspecialchars($fila['nombre_medico']); ?></td>
                </tr>
                <tr>
                    <th>Especialización</th>
                    <td><?php echo htmlspecialchars($fila['especializacion']); ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo htmlspecialchars($fila['telefono_medico']); ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo htmlspecialchars($fila['correo_medico']); ?></td>
                </tr>
            </table>
        </div>
        <a href="generar_historial_pdf.php?id_cita=<?php echo urlencode($id_cita); ?>" class="boton-descargar">Descargar en PDF</a>
        <?php
            } else {
                echo "<p>No se encontraron datos para el ID de cita especificado.</p>";
            }
        } else {
            echo "<p>Error en la conexión a la base de datos.</p>";
        }
        ?>
    </div>
</body>
</html>
