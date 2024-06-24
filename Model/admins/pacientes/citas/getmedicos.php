<?php
require_once("../../../db/connection.php"); 
$db = new Database();
$con = $db->conectar();

$id_esp = $_POST['id_esp'];

$control = $con->prepare("SELECT * FROM medicos WHERE id_esp = ?");
$control->execute([$id_esp]);
$medicos = $control->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($medicos);
?>
