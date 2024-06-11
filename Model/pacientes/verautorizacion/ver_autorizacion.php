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
    <title>Autorización Médica</title>
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
            background-color: #2dcac1;
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

        .boton-descargar {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #a20000;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <img src="../../../assets/img/log.farma.png" alt="Logo" class="logo">
        <br><br><br><br><br>
        <h2>Autorización Médica</h2>
        <?php
        if ($con) {
            $consulta = $con->prepare("SELECT autorizaciones.*, 
                                  usuarios.documento AS doc_paciente, usuarios.nombre AS nombre_paciente, usuarios.apellido AS apellido_paciente, usuarios.telefono AS telefono_paciente, usuarios.direccion AS direccion_paciente,
                                  medicos.docu_medico, medicos.nombre_comple AS nombre_medico, medicamentos.nombre AS nombre_medicamento
                           FROM autorizaciones 
                           JOIN usuarios ON autorizaciones.documento = usuarios.documento 
                           JOIN medicos ON autorizaciones.docu_medico = medicos.docu_medico
                           JOIN medicamentos ON autorizaciones.id_medicamento = medicamentos.id_medicamento
                           WHERE autorizaciones.documento = :documento");


            $consulta->bindParam(':documento', $documento, PDO::PARAM_STR);
            $consulta->execute();
            if ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="seccion">
            <h3>Datos del Paciente</h3>
            <table class="datos">
                <tr>
                    <th>Documento</th>
                    <td><?php echo htmlspecialchars($fila['doc_paciente']); ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo htmlspecialchars($fila['nombre_paciente'] . ' ' . $fila['apellido_paciente']); ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo htmlspecialchars($fila['telefono_paciente']); ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><?php echo htmlspecialchars($fila['direccion_paciente']); ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos de la Autorización</h3>
            <table class="datos">
                <tr>
                    <th>Fecha</th>
                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                </tr>
                <tr>
                    <th>Medicamento</th>
                    <td><?php echo htmlspecialchars($fila['nombre_medicamento']); ?></td>
                </tr>
                <tr>
                    <th>Presentación</th>
                    <td><?php echo htmlspecialchars($fila['presentacion']); ?></td>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <td><?php echo htmlspecialchars($fila['cantidad']); ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Médico</h3>
            <table class="datos">
                <tr>
                    <th>Documento</th>
                    <td><?php echo htmlspecialchars($fila['docu_medico']); ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo htmlspecialchars($fila['nombre_medico']); ?></td>
                </tr>
            </table>
        </div>
        <a href="generar_autorizaciones_pdf.php?documento=<?php echo urlencode($documento); ?>" class="boton-descargar">Descargar en PDF</a>
        <?php
            } else {
                echo "<p>No se encontraron datos para el documento especificado.</p>";
            }
        } else {
            echo "<p>Error en la conexión a la base de datos.</p>";
        }
        ?>
    </div>
</body>
</html>
