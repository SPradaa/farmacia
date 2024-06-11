<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();



if (isset($_GET['cod_auto'])) {
    $cod_auto = $_GET['cod_auto'];

    $sql = $con->prepare("
        SELECT 
            autorizaciones.cod_auto, 
            autorizaciones.fecha, 
            usuarios.documento, 
            usuarios.nombre, 
            usuarios.apellido, 
            medicos.nombre_comple, 
            medicamentos.id_medicamento,
            medicamentos.nombre AS medicamento, 
            medicamentos.cantidad AS cantidad_disponible, 
            autorizaciones.presentacion,
            autorizaciones.cantidad,
            autorizaciones.fecha_hora_auto,
            autorizaciones.fecha_venc,
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
            autorizaciones.cod_auto = :cod_auto
    ");
    $sql->bindParam(':cod_auto', $cod_auto);
    $sql->execute();
    $autorizacion = $sql->fetch();

    if (!$autorizacion) {
        echo "<p>No se encontró la autorización.</p>";
        exit;
    }
} else {
    echo "<p>Código de autorización no proporcionado.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cantidad_entregada = $autorizacion['cantidad'];
    $id_medicamento = $autorizacion['id_medicamento'];

    // Restar la cantidad del medicamento
    $update_medicamento_sql = $con->prepare("
        UPDATE medicamentos 
        SET cantidad = cantidad - :cantidad_entregada 
        WHERE id_medicamento = :id_medicamento
    ");
    $update_medicamento_sql->bindParam(':cantidad_entregada', $cantidad_entregada);
    $update_medicamento_sql->bindParam(':id_medicamento', $id_medicamento);
    $update_medicamento_sql->execute();

    // Actualizar el estado de la autorización a entregado (o el id correspondiente en la tabla)
    $update_sql = $con->prepare("
        UPDATE autorizaciones 
        SET id_estado = 2 
        WHERE cod_auto = :cod_auto
    ");
    $update_sql->bindParam(':cod_auto', $cod_auto);
    $update_sql->execute();

    // Mostrar mensaje de éxito usando JavaScript y redirigir
    echo "<script>
        alert('Medicamentos entregados exitosamente.');
        window.location.href = 'autorizaciones_resultado.php?documento=" . $autorizacion['documento'] . "';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entregar Medicamentos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: -37px;
        }

        h1 {
            text-align: center;
            font-family: 'Arial Black', sans-serif;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            margin-top: -1px;
        }

        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 15%;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            padding: 12px;
            text-align: left;
            width: 55%;
        }

        label {
            margin-left: 15%;
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
            background-color: #046bcc;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px #999;
            margin-left: 40.5%;
        }

        .btn:hover {background-color: #046bcc}

        .btn:active {
            background-color: #046bcc;
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

        .boton {
            margin-left: -60%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="boton">
    <a href="autorizaciones_resultado.php" class="btn btn-secondary">Regresar</a>
</div>

<div class="contenedor">
    <h1>Confirmar Entrega de Medicamentos</h1>
    <form method="POST">
        <table>
            <tr>
                <th>Código Autorización</th>
                <td><?php echo htmlspecialchars($autorizacion['cod_auto']); ?></td>
            </tr>
            <tr>
                <th>Documento</th>
                <td><?php echo htmlspecialchars($autorizacion['documento']); ?></td>
            </tr>
            <tr>
                <th>Nombre del Paciente</th>
                <td><?php echo htmlspecialchars($autorizacion['nombre']); ?></td>
            </tr>
            <tr>
                <th>Apellido del Paciente</th>
                <td><?php echo htmlspecialchars($autorizacion['apellido']); ?></td>
            </tr>
            <tr>
                <th>Nombre del Médico</th>
                <td><?php echo htmlspecialchars($autorizacion['nombre_comple']); ?></td>
            </tr>
            <tr>
                <th>Nombre del Medicamento</th>
                <td><?php echo htmlspecialchars($autorizacion['medicamento']); ?></td>
            </tr>
            <tr>
                <th>Presentación</th>
                <td><?php echo htmlspecialchars($autorizacion['presentacion']); ?></td>
            </tr>
            <tr>
                <th>Cantidad</th>
                <td><?php echo htmlspecialchars($autorizacion['cantidad']); ?></td>
            </tr>
            <tr>
                <th>Fecha de Autorización</th>
                <td><?php echo htmlspecialchars($autorizacion['fecha_hora_auto']); ?></td>
            </tr>
            <tr>
                <th>Fecha de Vencimiento</th>
                <td><?php echo htmlspecialchars($autorizacion['fecha_venc']); ?></td>
            </tr>
        </table>
        <p>
            <label>
                <input type="checkbox" name="confirmar_entrega" required>
                Confirmo que se han entregado los medicamentos.
            </label>
        </p>
        <p>
            <input type="submit" value="Confirmar Entrega" class="btn">
        </p>
    </form>
</div>
</body>
</html>
