<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

$titulo = "Autorizaciones"; // Definir un título por defecto

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];

    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $sql->bindParam(':documento', $documento);
    $sql->execute();
    $usuario = $sql->fetch();

    if ($usuario) {
        // Aquí puedes mostrar los detalles de las autorizaciones o lo que necesites
        $titulo = "Autorizaciones del Paciente " . htmlspecialchars($usuario['nombre']) . " " . htmlspecialchars($usuario['apellido']);
    } else {
        $titulo = "El documento no se encontró.";
    }
}

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
        autorizaciones.id_estado = 1
    ORDER BY 
        autorizaciones.fecha DESC
");
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorizaciones</title>
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
        .button{
            margin-left: 13%;
            margin-top: -35px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1><?php echo $titulo; ?></h1>
        <form action="index.php">
            <input type="submit" value="Regresar" class="btn btn-secondary"/>
        </form>

        <!-- Botón para ver detalles de autorizaciones entregadas -->
         <div class="button">
        <form action="autorizaciones_entregadas.php">
            <input type="hidden" name="documento" value="<?php echo htmlspecialchars($documento); ?>">
            <input type="submit" value="Ver Detalle Auto Entregadas" class="btn btn-primary"/>
        </form>
        </div>
        
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
                <th>Acciones</th>
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
                    <td>
                        <form action="entregar_medicamento.php" method="GET">
                            <input type="hidden" name="cod_auto" value="<?php echo htmlspecialchars($fila['cod_auto']); ?>">
                            <input type="submit" value="Entregar Medicamentos" class="btn btn-primary">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>