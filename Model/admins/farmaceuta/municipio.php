<?php
require_once("../../../db/connection.php");

$conexion = new Database();
$con = $conexion->conectar();

$depar = $_POST['id_depart'];

$sql = "SELECT municipios.id_municipio, municipios.municipio FROM municipios 
        WHERE municipios.id_depart = :id_depart";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id_depart', $depar, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = "<option value=''>Seleccione el Municipio</option>";
foreach ($result as $row) {
    $options .= '<option value="' . $row['id_municipio'] . '">' . $row['municipio'] . '</option>';
}

echo $options;
?>
