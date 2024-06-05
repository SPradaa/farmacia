<?php
session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

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

        .logo{
            position: absolute;
            top: 80px;
            left: 120px;
            width: 100px;
        }

        h2 {
    text-align: center;
    margin-bottom: 40px; /* Aumenta este valor para mover el título más arriba */
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

    <br>
    <br>
    <br>
    <br>
    <br>
        <h2>Historial Clínico</h2>
        <?php
        $consulta = $con->prepare("SELECT * FROM histo_clinica 
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
                    <td><?php echo $fila['tipo']; ?></td>
                </tr>
                <tr>
                    <th>Documento</th>
                    <td><?php echo $fila['documento']; ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $fila['nombre']; ?></td>
                </tr>
                <tr>
                    <th>Apellido</th>
                    <td><?php echo $fila['apellido']; ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo $fila['telefono']; ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo $fila['correo']; ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><?php echo $fila['direccion']; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos de la Historia Clínica</h3>
            <table class="datos">
                <tr>
                    <th>Descripción</th>
                    <td><?php echo $fila['descripcion']; ?></td>
                </tr>
                <tr>
                    <th>Diagnóstico</th>
                    <td><?php echo $fila['diagnostico']; ?></td>
                </tr>
            </table>
        </div>
        <div class="seccion">
            <h3>Datos del Médico</h3>
            <table class="datos">
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $fila['nombre_comple']; ?></td>
                </tr>
                <tr>
                    <th>Especialización</th>
                    <td><?php echo $fila['especializacion']; ?></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><?php echo $fila['correo']; ?></td>
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
