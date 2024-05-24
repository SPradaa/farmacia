<?php
// session_start();
    require_once("../../../../db/connection.php"); 
    $db = new Database();
    $con = $db->conectar();
	echo"session start" ;

	
require_once("../../../../controller/seg.php");
validarSesion();


if (isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "formreg") {
    $especializacion = $_POST['id_esp'];

    if (!empty($especializacion)) {
        // Almacena el ID de la especialización en una variable de sesión
        $_SESSION['id_especializacion'] = $especializacion;
        
        // Redirige a "citas.php"
        header("Location: citas.php");
        exit;
    } else {
        echo '<script>alert("Por favor seleccione una opción")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>preview</title>
</head>

<body>
    <h2>Seleccione la especialidad que necesita</h2>
    <form method="post" action="">
        <select name="id_esp">
            <option value=""></option>

            <?php
            $control = $con->prepare("SELECT * from especializacion");
            $control->execute();
            while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value=" . $fila['id_esp'] . ">"
                    . $fila['especializacion'] . "</option>";
            }
            ?>

        </select>

        <input type="submit" name="validar" value="Consultar">
        <input type="hidden" name="MM_insert" value="formreg">
    </form>
</body>

</html>
