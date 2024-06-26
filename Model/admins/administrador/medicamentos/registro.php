<?php
require '../../../../vendor/autoload.php';
require_once("../../../../db/connection.php");
$db = new Database();
$conectar = $db->conectar();


require_once("../../../../controller/seg.php");
validarSesion();

use Picqer\Barcode\BarcodeGeneratorPNG;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formreg")) {
    $nombre = $_POST['nombre'];
    $clasificacion = $_POST['id_cla'];
    $presentacion = $_POST['presentacion'];
    $cantidad = $_POST['cantidad'];
    $cant = $_POST['medida_cant'];
    $laboratorio = $_POST['id_lab'];
    $fecha = $_POST['f_vencimiento'];
    $estado = $_POST['id_estado'];

    $codigo_barras = uniqid() . rand(1000, 9999);

    $generator = new BarcodeGeneratorPNG();
    $codigo_barras_imagen = $generator->getBarcode($codigo_barras, $generator::TYPE_CODE_128);

    file_put_contents(__DIR__ . '/../../images/' . $codigo_barras . '.png', $codigo_barras_imagen);

    $insertsql = $conectar->prepare("INSERT INTO medicamentos (nombre, id_cla, presentacion, cantidad, medida_cant, id_lab, f_vencimiento, id_estado, codigo_barras) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertsql->execute([$nombre, $clasificacion, $presentacion, $cantidad, $cant, $laboratorio, $fecha, $estado, $codigo_barras]);
    echo '<script>alert("Registro exitoso.");</script>';
    echo '<script>window.location="index_medicame.php"</script>';
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Medicamentos</title>
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../css/registromed.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- Añade jQuery -->
    <script>
        function validateField(regex, input, errorMessage) {
            const value = input.value;
            const isValid = regex.test(value);
            input.setCustomValidity(isValid ? "" : errorMessage);
            input.reportValidity();
            return isValid;
        }

        $(document).ready(function() {
            $("#nombre").on("input", function() {
                validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){4,30}$/, this, "Ingrese un nombre válido (solo letras)");
            });

            $("#presentacion").on("input", function() {
                validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){4,20}$/, this, "Ingrese un presentación válida de medicamentos (solo letras)");
            });

            $("#cantidad").on("input", function() {
                validateField(/^([a-zA-Z0-9\s]){4,15}$/, this, "Debe ingresar una cantidad válida(numeros y letras)");
            });

            $("#medida_cant").on("input", function() {
                validateField(/^([a-zA-Z0-9\s]){4,15}$/, this, "Debe ingresar una cantidad válida(numeros y letras)");
            });

        });

        function validateForm() {
            const isNombreValid = validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){4,30}$/, document.getElementById("nombre"), "ingrese un nombre válido (solo letras)");
            const isPresentacionValid = validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){4,20}$/, document.getElementById("presentacion"), "Ingrese un presentación válida de medicamentos (solo letras)");
            const isCantidadValid = validateField(/^([a-zA-Z0-9\s]){4,15}$/, document.getElementById("cantidad"), "Debe ingresar una cantidad válida(numeros y letras");
            const isMedida_cantValid = validateField(/^([a-zA-Z0-9\s]){4,15}$/, document.getElementById("medida_cant"), "Debe ingresar una cantidad válida(numeros y letras)");

            return isNombreValid && isNombreValid && isCantidadValid && isMedida_cantValid;
        }
    </script>
    <style>
        @media (max-width: 768px){
            .regresar{
                margin-top: 7px;
                margin-bottom: 70px;
            }
            input[type="submit"]{
                width: 70%;
                margin-top: -1px;
                margin-bottom: 2px;
                margin-left: 33px;
            }
            h1{
                margin-top: -60px; 
                margin-bottom: 50px; 
            }
            .login-box{
            margin-top: -5px;
            margin-bottom: -72px;
        }
        }
        .login-box{
            margin-top: -11px;

        }
        h1{
            margin-top: -40px;
            margin-bottom: 50px;
        }
        input[type="submit"]{
            margin-bottom: 20px;
            width: 60%;
            margin-left: 140px;
        -}
    </style>
    
</head>
<body>

    <div class="regresar">
        <div class="col-md-6">
            <form action="index_medicame.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
        </div>
        <div class="login-box">
        <img src="../../../../assets/img/log.farma.png">
        <h1>Crear Medicamento</h1>

        <form method="post" name="form1" id="form1" autocomplete="off"> 

            <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre del medicamento">

            <select name="id_cla" required>
                <option value="">Seleccione el Tipo de Medicamento</option>
                <?php
                    $control = $conectar->prepare("SELECT * FROM tipo_medicamento");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=" . $fila['id_cla'] . ">" . $fila['clasificacion'] . "</option>";
                    }
                ?>
            </select>

            <input type="text" name="presentacion" id="presentacion" placeholder="Ingrese la presentacion del medicamento">

            <input type="text" name="cantidad" id="cantidad" pattern="[0-9a-zA-Z]{2,20}" placeholder="Ingrese la cantidad">

            <input type="text" name="medida_cant" id="medida_cant" placeholder="Ingrese la cantidad de medida">

            <select name="id_lab">
                <option value="">Seleccione el laboratorio</option>
                <?php
                    $control = $conectar->prepare("SELECT * FROM laboratorio");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=" . $fila['id_lab'] . ">" . $fila['laboratorio'] . "</option>";
                    }
                ?>
            </select>

          <label for="f_vencimiento">Fecha de Vencimiento</label>
<input type="date" name="f_vencimiento" id="f_vencimiento">

<script>
    // Obtener el elemento del campo de fecha
    var fechaVencimiento = document.getElementById('f_vencimiento');

    // Obtener la fecha actual del sistema en formato ISO (YYYY-MM-DD)
    var fechaActual = new Date().toISOString().split('T')[0];

    // Establecer el atributo 'min' del campo de fecha para que no permita fechas pasadas
    fechaVencimiento.setAttribute('min', fechaActual);
</script>


            <br><br>

            <select name="id_estado">
                <option value="">Seleccione el estado del medicamento</option>
                <?php
                    $control = $conectar->prepare("SELECT * FROM estados WHERE id_estado IN ( 7, 8)");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=" . $fila['id_estado'] . ">" . $fila['estado'] . "</option>";
                    }
                ?>
            </select>

            <input type="submit" name="inicio" value="Crear">
            <input type="hidden" name="MM_insert" value="formreg">
        </form>
    </div>
              
</body>
</html>
