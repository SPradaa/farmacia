<?php
session_start();
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Asegúrate de que el usuario haya iniciado sesión
if (!isset($_SESSION['documento'])) {
    die("Usuario no autenticado.");
}

// Verificar si el parámetro 'documento' está presente en la URL
if (!isset($_GET['documento'])) {
    die("Documento no especificado.");
}

$documento = $_GET['documento'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico</title>
    <link rel="stylesheet" href="../../css/estilo.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .logo {
            position: absolute;
            top: 80px;
            left: 120px;
            width: 100px;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .seccion {
            margin-bottom: 30px;
        }

        .seccion h3 {
            background-color: #2dcac1;;
            color: #fff;
            padding: 5px;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .datos {
            border-collapse: collapse;
            width: 100%;
        }

        .datos th, .datos td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .datos th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <img src="../../../assets/img/log.farma.png" alt="Logo" class="logo">
        <br><br><br><br><br>
        <h2>Historial Clínico</h2>
        <?php
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
                                   WHERE histo_clinica.documento = :documento");
        $consulta->bindParam(':documento', $documento, PDO::PARAM_STR);
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
        <?php
        } else {
            echo "<p>No se encontraron datos para el documento especificado.</p>";
        }
        ?>
    </div>
</body>
</html>
