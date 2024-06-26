
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entregar Medicamentos</title>
    <link rel="stylesheet" href="usuarios/css/entregar_medicamento.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>  <style>
        .table-medicamentos {
            width: 100%;
            border-collapse: collapse;
        }
        .table-medicamentos th, .table-medicamentos td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-medicamentos th {
            background-color: #f2f2f2;
        }
        .disponible {
            color: green;
            font-weight: bold;
        }
        .no-disponible {
            color: red;
            font-weight: bold;
        }
        .btn[disabled] {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
    </style></style>
</head>
<body>
<div class="boton">
    <a href="autorizaciones_resultado.php" class="btn btn-secondary">Regresar</a>
</div>

<div class="contenedor">
    <h1>Confirmar Entrega de Medicamentos</h1>
    <?php
    require_once("../../../db/connection.php");
    $conexion = new Database();
    $con = $conexion->conectar();


require_once("../../../controller/seguridad.php");
validarSesion();





    if (isset($_GET['cod_auto'])) {
        $cod_auto = filter_input(INPUT_GET, 'cod_auto', FILTER_SANITIZE_STRING);

        if (empty($cod_auto)) {
            echo "<p>Código de autorización no proporcionado o inválido.</p>";
            exit;
        }

        $sql = $con->prepare("SELECT 
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
                autorizaciones.cod_auto = :cod_auto
        ");
        $sql->bindParam(':cod_auto', $cod_auto);
        $sql->execute();
        $autorizacion = $sql->fetch();

        if (!$autorizacion) {
            echo "<p>No se encontró la autorización.</p>";
            exit;
        }

        // Desencripta y decodifica el campo medicamento
        function decrypt($data) {
            // Implementa tu lógica de desencriptación aquí
            return $data; // Placeholder
        }

        $medicamentos_json = decrypt($autorizacion['medicamento']);
        $medicamentos = json_decode($medicamentos_json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<p>Error al decodificar la información de los medicamentos.</p>";
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Inicia una transacción para asegurar la consistencia de las operaciones
                $con->beginTransaction();

                // Actualizar el estado de la autorización a 2 (Entregado)
                $update_autorizacion_sql = $con->prepare("
                    UPDATE autorizaciones 
                    SET id_estado = 2 
                    WHERE cod_auto = :cod_auto
                ");
                $update_autorizacion_sql->bindParam(':cod_auto', $cod_auto);
                $update_autorizacion_sql->execute();

                // Actualizar las cantidades de los medicamentos en la tabla medicamentos
                foreach ($medicamentos as $medicamento) {
                    $id_medicamento = $medicamento['id'];

                    // Obtener la cantidad actual del medicamento en la base de datos
                    $sql_cantidad = $con->prepare("
                        SELECT cantidad
                        FROM medicamentos
                        WHERE id_medicamento = :id_medicamento
                    ");
                    $sql_cantidad->bindParam(':id_medicamento', $id_medicamento);
                    $sql_cantidad->execute();
                    $row = $sql_cantidad->fetch(PDO::FETCH_ASSOC);
                    $cantidad_bd = $row['cantidad'];

                    // Extraer la cantidad numérica de la base de datos (ej. 100UND -> 100)
                    $cantidad_numerica_bd = intval(preg_replace('/[^0-9]/', '', $cantidad_bd));

                    // Calcular la nueva cantidad restando la cantidad a entregar
                    $cantidad_a_entregar = intval($medicamento['cantidad']);
                    $nueva_cantidad_numerica = $cantidad_numerica_bd - $cantidad_a_entregar;

                    // Construir la nueva cantidad con "UND" al final
                    $nueva_cantidad = $nueva_cantidad_numerica . "UND";

                    // Actualizar la cantidad en la base de datos
                    $update_medicamento_sql = $con->prepare("
                        UPDATE medicamentos 
                        SET cantidad = :nueva_cantidad 
                        WHERE id_medicamento = :id_medicamento
                    ");
                    $update_medicamento_sql->bindParam(':nueva_cantidad', $nueva_cantidad);
                    $update_medicamento_sql->bindParam(':id_medicamento', $id_medicamento);
                    $update_medicamento_sql->execute();
                }

                // Confirmar la transacción
                $con->commit();

                echo "<script>
                    alert('Medicamentos entregados exitosamente.');
                    window.location.href = 'autorizaciones_resultado.php?documento=" . $autorizacion['documento'] . "';
                </script>";
                exit;
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $con->rollback();
                echo "<p>Error al confirmar la entrega: " . $e->getMessage() . "</p>";
            }
        }
    } else {
        echo "<p>Código de autorización no proporcionado.</p>";
        exit;
    }
    ?>
    <form method="POST">
        <table class="table-medicamentos">
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
                <td><?php echo htmlspecialchars($autorizacion['nombre'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Apellido del Paciente</th>
                <td><?php echo htmlspecialchars($autorizacion['apellido'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Nombre del Médico</th>
                <td><?php echo htmlspecialchars($autorizacion['nombre_comple'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Medicamentos</th>
                <td>
                    <table class="table-medicamentos">
                        <tr>
                            <th class="selector">ID</th>
                            <th class="selector">Nombre</th>
                            <th class="selector">Presentación</th>
                            <th class="selector">Cantidad</th>
                            <th class="selector">Disponibilidad</th>
                        </tr>
                        <?php foreach ($medicamentos as $medicamento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($medicamento['id']); ?></td>
                                <td><?php echo isset($medicamento['name']) ? htmlspecialchars($medicamento['name']) : 'N/A'; ?></td>
                                <td><?php echo isset($medicamento['presentacion']) ? htmlspecialchars($medicamento['presentacion']) : 'N/A'; ?></td>
                                <td><?php echo isset($medicamento['cantidad']) ? htmlspecialchars($medicamento['cantidad']) : 'N/A'; ?></td>
                                <?php
                                $id_medicamento = $medicamento['id'];
                                $cantidad_medicamento = intval($medicamento['cantidad']);
                                $sql_disponibilidad = $con->prepare("
                                    SELECT cantidad
                                    FROM medicamentos
                                    WHERE id_medicamento = :id_medicamento
                                ");
                                $sql_disponibilidad->bindParam(':id_medicamento', $id_medicamento);
                                $sql_disponibilidad->execute();
                                $resultado = $sql_disponibilidad->fetch(PDO::FETCH_ASSOC);
                                $cantidad_disponible = intval(preg_replace('/[^0-9]/', '', $resultado['cantidad']));

                                if ($cantidad_disponible >= $cantidad_medicamento) {
                                    $disponibilidad_texto = "Disponible";
                                    $disponibilidad_class = "disponible";
                                } else {
                                    $disponibilidad_texto = "No disponible";
                                    $disponibilidad_class = "no-disponible";
                                }
                                ?>
                                <td class="<?php echo $disponibilidad_class; ?>"><?php echo $disponibilidad_texto; ?></td>
                            </tr>
                        <?php endforeach; ?>

                        
                    </table>
                </td>
            </tr>
            <tr>
                <th>Fecha de Autorización</th>
                <td><?php echo htmlspecialchars($autorizacion['fecha']); ?></td>
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
            <button type="submit" class="btn btn-primary">Confirmar Entrega</button>
        </p>
               </form>
        </div>
    </body>
</html>

