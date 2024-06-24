<?php
session_start();
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Asegúrate de que el usuario haya iniciado sesión
if (!isset($_SESSION['documento'])) {
    die("Usuario no autenticado.");
}

$user_document = $_SESSION['documento'];

// Modificar la consulta para seleccionar todas las autorizaciones del paciente
$sentencia_select = $con->prepare("
    SELECT 
       *
    FROM autorizaciones
    JOIN usuarios ON autorizaciones.documento = usuarios.documento
    JOIN medicos ON autorizaciones.docu_medico = medicos.docu_medico
    
    WHERE autorizaciones.documento = :user_document
    ORDER BY autorizaciones.fecha ASC
");
$sentencia_select->bindParam(':user_document', $user_document, PDO::PARAM_STR);
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorizaciones</title>
    <link rel="stylesheet" href="../css/autoes.css">
    <style>
        /* Estilos para la tabla interna del medicamento */
        .inner-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px; /* Añadido margen arriba */
        }
        .inner-table th, .inner-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .inner-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>AUTORIZACIONES</h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <?php if (isset($_GET['btn_buscar'])): ?>
                    <form action="autorizaciones.php" method="get">
                        <input type="submit" value="Regresar" class="btn btn-secondary"/>
                    </form>
                <?php else: ?>
                    <form action="../autorizaciones.php">
                        <input type="submit" value="Regresar" class="btn btn-secondary"/>
                    </form>
                <?php endif; ?>
            </div>
            <div class="barra_buscador">
                <form action="autorizaciones.php" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar Autorización" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>
            <table>
                <tr class="head">
                    <th>Código Autorización</th>
                    <th>Fecha</th>
                    <th>Nombre Paciente</th>
                    <!-- <th>Apellido Paciente</th> -->
                    <th>Nombre Médico</th>
                    <th>Medicamento</th>
                    <th>Fecha Vencimiento</th>
                    <th>Acción</th>
                </tr>
                <?php
                foreach ($resultado as $fila) {
                    // Decodificar el JSON contenido en el campo 'medicamento'
                    $medicamento_data = json_decode($fila['medicamento'], true);

                    // Comprobar si se pudo decodificar correctamente
                    if ($medicamento_data !== null) {
                        // Imprimir una tabla dentro de la celda
                        echo "<tr>";
                        echo "<td>{$fila['cod_auto']}</td>";
                        echo "<td>{$fila['fecha']}</td>";
                        echo "<td>{$fila['nombre']}{$fila['apellido']}</td>";
                        // echo "<td>{$fila['apellido']}</td>";
                        echo "<td>{$fila['nombre_comple']}</td>";
                        echo "<td>";
                        echo "<table class='inner-table'>";
                        echo "<tr><th>ID</th><th>Nombre</th><th>Presentación</th><th>Cantidad</th></tr>";
                        foreach ($medicamento_data as $med) {
                            echo "<tr>";
                            echo "<td>{$med['id']}</td>";
                            echo "<td>{$med['name']}</td>";
                            echo "<td>{$med['presentacion']}</td>";
                            echo "<td>{$med['cantidad']}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "</td>";
                       
                        echo "<td>{$fila['fecha_venc']}</td>";
                        echo "<td><a href='' class='btn__autorizar' onclick=\"window.open('../verautorizacion/ver_autorizacion.php?id_cita={$fila['id_cita']}','','width=1000,height=700,toolbar=NO');void(null);\">Ver Auto.Medica</a></td>";
                        echo "</tr>";
                    } else {
                        // Manejar el caso en que el JSON no se pueda decodificar correctamente
                        echo "<tr>";
                        echo "<td colspan='10'>Error al procesar datos de medicamento</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
