<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if (!isset($_GET['nit'])) {
    echo '<script>alert("No se ha proporcionado ningún ID.");</script>';
    echo '<script>window.location="index_emp.php"</script>';
    exit;
}

$nit = $_GET['nit'];
$sql = $con->prepare("SELECT * FROM empresas, estados WHERE empresas.id_estado = estados.id_estado AND empresas.nit = :nit");
$sql->bindParam(':nit', $nit, PDO::PARAM_STR);
$sql->execute();
$usua = $sql->fetch();

if (!$usua) {
    echo '<script>alert("No se encontró la empresa.");</script>';
    echo '<script>window.location="index_emp.php"</script>';
    exit;
}
?>

<?php
if (isset($_POST["update"])) {
    $licencia = $_POST['licencia'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $estado = $_POST['id_estado'];

    if ($licencia == "" || $inicio == "" || $fin == "" || $estado == "") {
        echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
        echo '<script>window.location="index_emp.php"</script>';
    } else {
        $updateSQL = $con->prepare("UPDATE empresas SET licencia = :licencia, inicio = :inicio, fin = :fin, id_estado = :id_estado WHERE nit = :nit");
        $updateSQL->bindParam(':licencia', $licencia, PDO::PARAM_STR);
        $updateSQL->bindParam(':inicio', $inicio, PDO::PARAM_STR);
        $updateSQL->bindParam(':fin', $fin, PDO::PARAM_STR);
        $updateSQL->bindParam(':nit', $nit, PDO::PARAM_STR);
        $updateSQL->bindParam(':id_estado', $estado, PDO::PARAM_STR);
        $updateSQL->execute();
        
        echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';
        echo '<script>window.location="index_emp.php"</script>';
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../css/edi_emp.css">
    <title>Actualizar Empresa</title>
    <style>
    @media (max-width: 768px) {
    input[type="date"]{
        width: calc(47% - 10px);
        margin-left: 8px;
    }
    label{
        margin-left: 8px;
    }
    select{
        width: calc(98% - 10px);
        margin-top: -20px;
    }
    input[type="submit"]{
        width: calc(48% - 10px);
        margin-left: 120px;
        margin-top: 8px;
        margin-bottom: 10px;
    }

  }
    </style>
</head>
<body>
<div class="regresar">
        <div class="col-md-6">
            <form action="index_emp.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
        </div>
<div class="login-box">
<img src="../../../../assets/img/log.farma.png">
        <h1>Editar Empresa</h1>
        <form method="POST" name="formreg" autocomplete="off">
        <div class="row">
                <input type="text" name="nit" title="El nit debe tener solo numeros (10 digitos)" value="<?php echo $usua['nit']; ?>" disabled>
                <input type="text" name="empresa" pattern="[a-zA-Z ]{4,30}" title="La empresa debe tener solo letras" value="<?php echo $usua['empresa']; ?>" disabled>
            </div>
            <div class="row">
                <input type="text" name="licencia" id="licencia" value="" placeholder="Genere una nueva licencia">
                <input type="button" onclick="generate()" value="Nueva Licencia" class="generate"></button>
            </div>
            <div class="row">
                <label for="nombre">Fecha inicio licencia </label>
                <input type="date" name="inicio" id="nombre" placeholder="Ingrese la fecha de inicio de la licencia" value="<?php echo date('Y-m-d'); ?>">  
            
                <label for="nombre">Fecha expiracion licencia </label>
                <?php
                $fechaInicio = date('Y-m-d');
                $fechaFin = date('Y-m-d', strtotime('+1 year', strtotime($fechaInicio)));
                echo '<input type="date" name="fin" id="nombre" value="' . $fechaFin . '">';
                ?>
            </div>
            <div class="row">
            <select name="id_estado">
                    <option value="<?php echo $usua['id_estado']?>"><?php echo $usua['estado']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM estados WHERE id_estado =3 OR id_estado=4");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_estado'] . ">" . $fila['estado'] . "</option>";
                        }
                    ?>
                </select>
                </div>
            <input type="submit" name="update" value="Actualizar">
        </form>
    </div>
    <script>
    function generate() {
        var caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";
        var longitud = 10;
        var nuevaLicencia = Array.from({ length: longitud }, () => caracteres.charAt(Math.floor(Math.random() * caracteres.length))).join('');
        document.getElementById("licencia").value = nuevaLicencia;
    }
    </script>
</body>
</html>
