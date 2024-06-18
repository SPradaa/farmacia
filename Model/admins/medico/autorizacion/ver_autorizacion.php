<?php
session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

$id_cita = $_GET['id_cita'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AUTORIZACION</title>
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
            background-color: #333;
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
        <img src="../../../../assets/img/log.farma.png" alt="Logo" class="logo">
        <br><br><br><br><br>
        <h2>AUTORIZACIÓN</h2>
        <?php
        $consulta = $con->prepare("SELECT autorizaciones.*, 
                                    usuarios.documento AS doc_usuario, usuarios.nombre AS nombre_usuario, usuarios.apellido AS apellido_usuario, usuarios.telefono AS telefono_usuario, usuarios.correo AS correo_usuario, usuarios.direccion AS direccion_usuario,
                                    medicos.nombre_comple AS nombre_medico, medicos.telefono AS telefono_medico, medicos.correo AS correo_medico, 
                                    t_documento.tipo AS tipo_doc,
                                    especializacion.especializacion,
                                    autorizaciones.cod_auto, autorizaciones.id_cita, autorizaciones.presentacion, autorizaciones.cantidad, autorizaciones.fecha, autorizaciones.fecha_venc, 
                                    medicamentos.nombre AS nombre_medicamento
                            FROM autorizaciones 
                            JOIN usuarios ON autorizaciones.documento = usuarios.documento 
                            JOIN medicos ON autorizaciones.docu_medico = medicos.docu_medico
                            JOIN t_documento ON usuarios.id_doc = t_documento.id_doc 
                            JOIN especializacion ON medicos.id_esp = especializacion.id_esp
                            JOIN medicamentos ON autorizaciones.id_medicamento = medicamentos.id_medicamento
                            WHERE autorizaciones.id_cita = :id_cita");
                            
        $consulta->bindParam(':id_cita', $id_cita, PDO::PARAM_STR);
        $consulta->execute();
        if ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
        // Procesar resultados

        ?>

        <div class="seccion">
            <h3>Fecha Autorización</h3>
            <table class="datos">
                <tr>
                    <th>Fecha</th>
                    <td><?php echo $fila['fecha']; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Paciente</h3>
            <table class="datos">
                <tr>
                    <th>Tipo de Documento</th>
                    <td><?php echo $fila['tipo_doc']; ?></td>
                </tr>
                <tr>
                    <th>Documento</th>
                    <td><?php echo $fila['doc_usuario']; ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $fila['nombre_usuario']; ?></td>
                </tr>
                <tr>
                    <th>Apellido</th>
                    <td><?php echo $fila['apellido_usuario']; ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo $fila['telefono_usuario']; ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo $fila['correo_usuario']; ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><?php echo $fila['direccion_usuario']; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos de la Autorización</h3>
            <table class="datos">
                <tr>
                    <th>Código de la Autorización</th>
                    <td><?php echo $fila['cod_auto']; ?></td>
                </tr>
                <tr>
                    <th>Medicamento</th>
                    <td><?php echo $fila['nombre_medicamento']; ?></td>
                </tr>
                <tr>
                    <th>Presentación</th>
                    <td><?php echo $fila['presentacion']; ?></td>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <td><?php echo $fila['cantidad']; ?></td>
                </tr>
                <tr>
                    <th>Fecha de Vencimiento</th>
                    <td><?php echo $fila['fecha_venc']; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Médico</h3>
            <table class="datos">
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $fila['nombre_medico']; ?></td>
                </tr>
                <tr>
                    <th>Especialización</th>
                    <td><?php echo $fila['especializacion']; ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo $fila['telefono_medico']; ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo $fila['correo_medico']; ?></td>
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
