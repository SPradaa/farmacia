<?php
session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

$sentencia_select = $con->prepare("SELECT medicamentos.*, tipo_medicamento.clasificacion, laboratorio.laboratorio, estados.estado
                                   FROM medicamentos
                                   JOIN tipo_medicamento ON medicamentos.id_cla = tipo_medicamento.id_cla
                                   JOIN laboratorio ON medicamentos.id_lab = laboratorio.id_lab
                                   JOIN estados ON medicamentos.id_estado = estados.id_estado
                                   ORDER BY medicamentos.nombre ASC");
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();

if (isset($_GET['btn_buscar'])) {
    $buscar = $_GET['buscar'];
    $consulta = $con->prepare("SELECT medicamentos.*, tipo_medicamento.clasificacion, laboratorio.laboratorio, estados.estado
                               FROM medicamentos
                               JOIN tipo_medicamento ON medicamentos.id_cla = tipo_medicamento.id_cla
                               JOIN laboratorio ON medicamentos.id_lab = laboratorio.id_lab
                               JOIN estados ON medicamentos.id_estado = estados.id_estado
                               WHERE medicamentos.nombre LIKE :buscar
                               ORDER BY medicamentos.nombre ASC");
    $buscar = "%$buscar%";
    $consulta->bindParam(':buscar', $buscar, PDO::PARAM_STR);
    $consulta->execute();
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['codigo_barras']) && !empty($_GET['codigo_barras'])) {
    $codigo_barras = $_GET['codigo_barras'];
    $consulta = $con->prepare("SELECT medicamentos.*, tipo_medicamento.clasificacion, laboratorio.laboratorio, estados.estado
                               FROM medicamentos
                               JOIN tipo_medicamento ON medicamentos.id_cla = tipo_medicamento.id_cla
                               JOIN laboratorio ON medicamentos.id_lab = laboratorio.id_lab
                               JOIN estados ON medicamentos.id_estado = estados.id_estado
                               WHERE medicamentos.codigo_barras = :codigo_barras");
    $consulta->bindParam(':codigo_barras', $codigo_barras, PDO::PARAM_STR);
    $consulta->execute();
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function getRowClass($fila) {
    $fechaVencimiento = new DateTime($fila['f_vencimiento']);
    $hoy = new DateTime();
    $intervalo = $hoy->diff($fechaVencimiento);
    $diasParaVencer = (int)$intervalo->format('%a');

    if ($fila['cantidad'] == 0) {
        return 'medicamento-rojo';
    } elseif ($fila['cantidad'] <= 10) {
        return 'medicamento-amarillo';
    } elseif ($diasParaVencer <= 10) {
        return 'medicamento-naranja';
    }

    return '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Medicamentos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .codigo-barras-container {
            text-align: center;
        }
        .codigo-barras {
            font-size: 14px;
            margin-top: 5px;
            color: #333;
        }
        .medicamento-naranja {
            background-color: orange;
        }
        .medicamento-amarillo {
            background-color: yellow;
        }
        .medicamento-rojo {
            background-color: red;
        }

        .container {
            margin-bottom: 20px;
            background-color: #b6e0f9;
            width: 260px;
            height: 40px;
            border-radius: 7px;
            margin-top: 16px;
            margin-left: 10%;
        }

        .naranja {
            margin-bottom: 20px;
            background-color: #b6e0f9;
            width: 280px;
            height: 40px;
            border-radius: 7px;
            margin-left: 38%;
            margin-top: -60px;
        }

        .rojo{
            margin-bottom: 20px;
            background-color: #b6e0f9;
            width: 245px;
            height: 40px;
            border-radius: 7px;
            margin-left: 67%;
            margin-top: -60px;
        }
        .subtitulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
            padding-left: 8px ;
            padding-top: 10px;
        }
        .icon {
            margin-right: 10px;
        }
        .icons {
            justify-content: space-between;
        }

        .espacios{
            margin-top: -60px;
            width: 100%;
            height: auto;
            margin-left: 34%;
            /* background: red; */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
        }
        .btn.btn-danger{
            background: red;
            border: 1px solid red;
        }
        .btn.btn-primary{
            background: green;
            border: 1px solid green;
        }

    </style>
</head>
<body>
    <div class="contenedor">
        <h2>MEDICAMENTOS REGISTRADOS</h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <?php if (isset($_GET['btn_buscar']) || isset($_GET['codigo_barras'])): ?>
                    <form action="index_medicame.php" method="get">
                        <input type="submit" value="Regresar" class="btn btn-secondary"/>
                    </form>
                <?php else: ?>
                    <form action="../medicamentos.php">
                        <input type="submit" value="Regresar" class="btn btn-secondary"/>
                    </form>
                <?php endif; ?>
            </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar Medicamento" class="input_text">
                    <select name="codigo_barras" id="codigo_barras">
                        <option value="">Seleccione un código de barras</option>
                        <?php
                            $consulta_codigos = $con->prepare("SELECT codigo_barras FROM medicamentos");
                            $consulta_codigos->execute();
                            while ($fila = $consulta_codigos->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value=\"" . $fila['codigo_barras'] . "\">" . $fila['codigo_barras'] . "</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>

            <div class="espacios">
            <div >
            <form action="generar_pdf.php" method="post">
                <button type="submit" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 384 512"><path fill="#ffffff" d="M181.9 256.1c-5-16-4.9-46.9-2-46.9c8.4 0 7.6 36.9 2 46.9m-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7c18.3-7 39-17.2 62.9-21.9c-12.7-9.6-24.9-23.4-34.5-40.8M86.1 428.1c0 .8 13.2-5.4 34.9-40.2c-6.7 6.3-29.1 24.5-34.9 40.2M248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24m-8 171.8c-20-12.2-33.3-29-42.7-53.8c4.5-18.5 11.6-46.6 6.2-64.2c-4.7-29.4-42.4-26.5-47.8-6.8c-5 18.3-.4 44.1 8.1 77c-11.6 27.6-28.7 64.6-40.8 85.8c-.1 0-.1.1-.2.1c-27.1 13.9-73.6 44.5-54.5 68c5.6 6.9 16 10 21.5 10c17.9 0 35.7-18 61.1-61.8c25.8-8.5 54.1-19.1 79-23.2c21.7 11.8 47.1 19.5 64 19.5c29.2 0 31.2-32 19.7-43.4c-13.9-13.6-54.3-9.7-73.6-7.2M377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9m-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9c37.1 15.8 42.8 9 42.8 9"/></svg>
                PDF</button>
            </form>
            </div>

            <div >
            <form action="generar_reporte_excel.php" method="post">
                <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 384 512">
                <path fill="#ffffff" d="M181.9 256.1c-5-16-4.9-46.9-2-46.9c8.4 0 7.6 36.9 2 46.9m-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7c18.3-7 39-17.2 62.9-21.9c-12.7-9.6-24.9-23.4-34.5-40.8M86.1 428.1c0 .8 13.2-5.4 34.9-40.2c-6.7 6.3-29.1 24.5-34.9 40.2M248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24m-8 171.8c-20-12.2-33.3-29-42.7-53.8c4.5-18.5 11.6-46.6 6.2-64.2c-4.7-29.4-42.4-26.5-47.8-6.8c-5 18.3-.4 44.1 8.1 77c-11.6 27.6-28.7 64.6-40.8 85.8c-.1 0-.1.1-.2.1c-27.1 13.9-73.6 44.5-54.5 68c5.6 6.9 16 10 21.5 10c17.9 0 35.7-18 61.1-61.8c25.8-8.5 54.1-19.1 79-23.2c21.7 11.8 47.1 19.5 64 19.5c29.2 0 31.2-32 19.7-43.4c-13.9-13.6-54.3-9.7-73.6-7.2M377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9m-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9c37.1 15.8 42.8 9 42.8 9"/></svg>
                Excel</button>
            </form>
            </div>
            </div>

            <div class="icons">
            <div class="container">
                <div class="subtitulo"><i class="fas fa-exclamation-circle icon" style="color: orange;"></i>Medicamento Por vencer</div></div>
                <div class="naranja">
                <div class="subtitulo"><i class="fas fa-exclamation-triangle icon" style="color: yellow;"></i>Medicamento Por Agotarse</div></div>
                <div class="rojo">
                <div class="subtitulo"><i class="fas fa-times-circle icon" style="color: red;"></i>Medicamento Agotado</div>
            </div>
            </div>
            </div><br>
            
            <table class="tabla">
                <tr class="head">
                    <td>Nombre</td>
                    <td>Tipo de Medicamento</td>
                    <td>Cantidad_Unidad</td>
                    <td>Medida Cantidad</td>
                    <td>Laboratorio</td>
                    <td>Fecha de Vencimiento</td>
                    <td>Código de Barras</td>
                    <td>Estado</td>
                </tr>
                <?php
                if (isset($resultados)) {
                    foreach ($resultados as $fila) {
                        $rowClass = getRowClass($fila);
                ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['clasificacion']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo $fila['medida_cant']; ?></td>
                        <td><?php echo $fila['laboratorio']; ?></td>
                        <td><?php echo $fila['f_vencimiento']; ?></td>
                        <td class="codigo-barras-container">
                            <img src="../../desarrollador/medicamentos/images/<?= $fila["codigo_barras"] ?>.png">
                            <span class="codigo-barras"><?php echo $fila['codigo_barras']; ?></span>
                        </td>
                        <td><?php echo $fila['estado']; ?></td>
                    </tr>
                <?php
                    }
                } else {
                    $consulta = $con->prepare("SELECT medicamentos.*, tipo_medicamento.clasificacion, laboratorio.laboratorio, estados.estado
                                               FROM medicamentos
                                               JOIN tipo_medicamento ON medicamentos.id_cla = tipo_medicamento.id_cla
                                               JOIN laboratorio ON medicamentos.id_lab = laboratorio.id_lab
                                               JOIN estados ON medicamentos.id_estado = estados.id_estado
                                               ORDER BY medicamentos.nombre ASC");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        $rowClass = getRowClass($fila);
                ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['clasificacion']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo $fila['medida_cant']; ?></td>
                        <td><?php echo $fila['laboratorio']; ?></td>
                        <td><?php echo $fila['f_vencimiento']; ?></td>
                        <td class="codigo-barras-container">
                            <img src="../../desarrollador/medicamentos/images/<?= $fila["codigo_barras"] ?>.png">
                            <span class="codigo-barras"><?php echo $fila['codigo_barras']; ?></span>
                        </td>
                        <td><?php echo $fila['estado']; ?></td>
                    </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>
