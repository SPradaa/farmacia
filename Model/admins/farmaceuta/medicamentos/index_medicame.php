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
    <link rel="stylesheet" href="../../css/estilo.css">
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
            margin-left: 15%;
        }

        .naranja {
            margin-bottom: 20px;
            background-color: #b6e0f9;
            width: 280px;
            height: 40px;
            border-radius: 7px;
            margin-left: 40%;
            margin-top: -60px;
        }

        .rojo{
            margin-bottom: 20px;
            background-color: #b6e0f9;
            width: 245px;
            height: 40px;
            border-radius: 7px;
            margin-left: 66.5%;
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
