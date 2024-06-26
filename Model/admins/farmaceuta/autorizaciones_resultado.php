<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

require_once("../../../controller/seguridad.php");
validarSesion();


$titulo = "Autorizaciones"; // Definir un título por defecto
$documento = isset($_GET['documento']) ? $_GET['documento'] : null;

if ($documento) {
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
        autorizaciones.*, usuarios.nombre AS nombre_usuario, usuarios.apellido, medicos.nombre_comple, estados.estado
    FROM 
        autorizaciones
    JOIN 
        usuarios ON autorizaciones.documento = usuarios.documento
    JOIN 
        medicos ON autorizaciones.docu_medico = medicos.docu_medico
    JOIN 
        estados ON autorizaciones.id_estado = estados.id_estado
    WHERE 
        autorizaciones.id_estado = 13 AND
        autorizaciones.documento = :documento
    ORDER BY 
        autorizaciones.fecha DESC
");
$sentencia_select->bindParam(':documento', $documento);
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll(PDO::FETCH_ASSOC);

// Función para generar la lista de medicamentos
function generarListaMedicamentos($medicamento) {
    $html = '<ul>';

    $medicamento_array = json_decode($medicamento, true);

    if ($medicamento_array && is_array($medicamento_array)) {
        foreach ($medicamento_array as $med) {
            $nombre = isset($med['name']) ? htmlspecialchars($med['name']) : 'Nombre no disponible';
            $cantidad = isset($med['cantidad']) ? htmlspecialchars($med['cantidad']) : 'Cantidad no disponible';

            $html .= '<li>' . $nombre . ': ' . $cantidad . '</li>';
        }
    } else {
        $html .= '<li>Datos de medicamento no disponibles o formato incorrecto</li>';
    }

    $html .= '</ul>';
    return $html;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorizaciones</title>
    <link rel="stylesheet" href="../../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="usuarios/css/autorizaciones_resultado.css">
</head>
<body>
    <div class="contenedor">
        <h1><?php echo $titulo; ?></h1>
     

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
                <th>Nombre del Médico</th>
                <th>Medicamento</th>
                <th>Fecha de Autorización</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($resultado as $fila) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['cod_auto']); ?></td>
                    <td><?php echo htmlspecialchars($fila['documento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre_usuario'] . ' ' . $fila['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre_comple']); ?></td>
                    <td>
                        <?php 
                        echo generarListaMedicamentos($fila['medicamento']);
                        ?>
                    </td>
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

