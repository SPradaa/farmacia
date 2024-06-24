<?php
$conexion = new mysqli("localhost", "root", "", "vitalfarma");

if ($conexion->connect_error) {
    die("La conexión ha fallado: " . $conexion->connect_error);
}

$depar = $_POST['id_depart'];

$sql = "SELECT id_municipio, municipio FROM municipios WHERE id_depart = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $depar);
$stmt->execute();
$result = $stmt->get_result();

$options = "<option value=''>Seleccione el Municipio</option>";
while ($ver = $result->fetch_assoc()) {
    $options .= '<option value="' . $ver['id_municipio'] . '">' . $ver['municipio'] . '</option>';
}

echo $options;
$stmt->close();
$conexion->close();
?>