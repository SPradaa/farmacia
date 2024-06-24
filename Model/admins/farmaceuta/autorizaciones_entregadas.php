<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

$titulo = "Autorizaciones Entregadas"; // Título por defecto

// Obtener documento del paciente desde el GET
if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];

    // Calcular fecha hace 3 meses
    $fecha_hace_tres_meses = date('Y-m-d', strtotime('-3 months'));

    // Consulta para obtener autorizaciones entregadas desde hace 3 meses
    $sentencia_select = $con->prepare("
        SELECT 
          *
        FROM 
            autorizaciones
        JOIN 
            usuarios ON autorizaciones.documento = usuarios.documento
        JOIN 
            medicos ON autorizaciones.docu_medico = medicos.docu_medico
       
        JOIN 
            estados ON autorizaciones.id_estado = estados.id_estado
        WHERE 
            autorizaciones.id_estado = 2 AND 
            autorizaciones.fecha >= :fecha_hace_tres_meses AND
            usuarios.documento = :documento
        ORDER BY 
            autorizaciones.fecha DESC
    ");
    $sentencia_select->bindParam(':fecha_hace_tres_meses', $fecha_hace_tres_meses);
    $sentencia_select->bindParam(':documento', $documento);
    $sentencia_select->execute();
    $resultado = $sentencia_select->fetchAll();

    // Actualizar título para reflejar el rango de fechas
    $titulo = "Autorizaciones Entregadas desde " . date('d/m/Y', strtotime($fecha_hace_tres_meses)) . " hasta hoy";
} else {
    // Si no se proporcionó el documento, puedes manejarlo aquí
    echo "<p>Documento del paciente no proporcionado.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorizaciones Entregadas</title>
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="stylesheet" href="usuarios/css/autorizaciones_entregadas.css">
    <style>
        .nested-table {
            border-collapse: collapse;
            width: 100%;
        }
        .nested-table th, .nested-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .nested-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1><?php echo $titulo; ?></h1>
       
        
        <table class="tabla">
            <tr class="head">
                <th>Código Autorización</th>
                <th>Documento</th>
                <th>Nombre</th>
               
                <th>Nombre del Médico</th>
                <th>Medicamento</th>
                <th>Fecha de Autorización</th>
                <th>Estado</th>
            </tr>
            <?php foreach ($resultado as $fila) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['cod_auto']); ?></td>
                    <td><?php echo htmlspecialchars($fila['documento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre']),($fila['apellido']) ;?></td>
             
                    <td><?php echo htmlspecialchars($fila['nombre_comple']); ?></td>
                    <td>
                        <?php
                            // Decodificar el JSON del campo 'medicamento'
                            $medicamento_data = json_decode($fila['medicamento'], true);
                            
                            // Imprimir una tabla dentro de la celda para mostrar los datos del medicamento
                            echo '<table class="nested-table">';
                   
                            foreach ($medicamento_data as $med) {
                                echo '<tr>';
                               
                                echo '<td>' . htmlspecialchars($med['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($med['presentacion']) . '</td>';
                                echo '<td>' . htmlspecialchars($med['cantidad']) . '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($fila['estado']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
