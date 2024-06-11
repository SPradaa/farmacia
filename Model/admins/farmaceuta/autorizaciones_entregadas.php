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
            autorizaciones.cod_auto, 
            autorizaciones.fecha, 
            usuarios.documento, 
            usuarios.nombre, 
            usuarios.apellido, 
            medicos.nombre_comple, 
            medicamentos.nombre AS medicamento, 
            estados.estado 
        FROM 
            autorizaciones
        JOIN 
            usuarios ON autorizaciones.documento = usuarios.documento
        JOIN 
            medicos ON autorizaciones.docu_medico = medicos.docu_medico
        JOIN 
            medicamentos ON autorizaciones.id_medicamento = medicamentos.id_medicamento
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            font-family: 'Arial Black', sans-serif;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #05a0b8;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color:#046bcc;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px #999;
        }

        .btn:hover {background-color: #046bcc}

        .btn:active {
            background-color:#046bcc;
            box-shadow: 0 2px #666;
            transform: translateY(2px);
        }

        .btn-secondary {
            background-color: #046bcc;
        }

        .btn-secondary:hover {
            background-color: #046bcc;
        }

        .btn-secondary:active {
            background-color: #046bcc;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1><?php echo $titulo; ?></h1>
        <form action="autorizaciones_resultado.php">
            <input type="submit" value="Regresar" class="btn btn-secondary"/>
        </form>
        
        <table class="tabla">
            <tr class="head">
                <th>Código Autorización</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Nombre del Médico</th>
                <th>Nombre del Medicamento</th>
                <th>Fecha de Autorización</th>
                <th>Estado</th>
                <!-- Aquí puedes agregar más columnas si lo necesitas -->
            </tr>
            <?php foreach ($resultado as $fila) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['cod_auto']); ?></td>
                    <td><?php echo htmlspecialchars($fila['documento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre_comple']); ?></td>
                    <td><?php echo htmlspecialchars($fila['medicamento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($fila['estado']); ?></td>
                    <!-- Puedes agregar más columnas según tu necesidad -->
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
