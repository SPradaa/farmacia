<?php
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>
<?php
$sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();
echo"conectado";

$documento=$_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
$direccion = $_SESSION['direccion'];
$telefono =$_SESSION['telefono'];
$correo = $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$empresa = $_SESSION[ 'nit'];

$nombre_comple = $nombre .''.$apellido; 

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;


}
?>

<?php

if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
{
    $id_doc= $_POST['id_doc'];
    $docu_medico= $_POST['docu_medico'];
    $nombre_comple= $_POST['nombre_comple'];
    $correo= $_POST['correo'];
    $telefono= $_POST['telefono'];
    $clave= $_POST['password'];
    $id_rol= $_POST['id_rol'];
    $id_estado= $_POST['id_estado'];
    $id_esp= $_POST['id_esp'];
    $nit = $empresa;

    $sql= $con -> prepare ("SELECT * FROM medicos WHERE docu_medico='$docu_medico'");
    $sql -> execute();
    $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);

    if ($fila){
       echo '<script>alert ("EL DOCUMENTO YA EXISTE //CAMBIELO//");</script>';
       echo '<script>window.location="create_medico.php"</script>';
    }
    else
 
   if ($id_doc=="" ||  $docu_medico=="" || $nombre_comple=="" || $telefono=="" || $correo=="" || $clave=="" || $id_rol=="" || $id_estado=="" || $id_esp=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="create_medico.php"</script>';
    }
    else
    {
      $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
      $insertSQL = $con->prepare("INSERT INTO medicos(id_doc, docu_medico, nombre_comple, telefono, correo, password, id_rol, id_estado, id_esp, nit) VALUES('$id_doc', '$docu_medico', '$nombre_comple', '$telefono', '$correo', '$pass_cifrado', '$id_rol', '$id_estado', '$id_esp', '$nit')");
      $insertSQL -> execute();
      echo '<script> alert("REGISTRO EXITOSO");</script>';
      echo '<script>window.location="index_medico.php"</script>';
      
  } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Medico</title>
    <link rel="stylesheet" href="../../css/usua.css">
</head>
<body>

    <div class="login-box">
        <h1>Crear Medicos</h1>

        <form method="post" name="form1" id="form1"  autocomplete="off"> 

        <select name="id_doc">
                <option value ="">Seleccione el Tipo de Documento</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from t_documento");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_doc'] . ">"
                     . $fila['tipo'] . "</option>";
                } 
                ?>
            </select>
        
            <input type="text" name="docu_medico" id="docu_medico" pattern="[0-9]{8,11}" placeholder="Digite su Documento" title="El documento debe tener solo números de 8 a 10 dígitos">

            <input type="text" name="nombre_comple" id="nombre_comple" pattern="[a-zA-ZÑñ ]{8,50}" placeholder="Ingrese su Nombre Completo" title="El nombre completo debe tener solo letras">

            <input type="text" name="telefono" id="telefono" pattern="[0-9]{10}" placeholder="Ingrese su Telefono" title="El telefono debe tener solo numeros (10 digitos)">

            <input type="text" name="correo" id="correo" pattern="[0-9a-zA-Z.@]{7,30}" placeholder="Ingrese su Correo" title="El correo debe tener minimo 10 letras y numeros">

            <input type="text" name="direccion" id="direccion" pattern="[a-zA-Z0-9#.-]{5,30}" placeholder="Ingrese la dirección">
            
             <input type="password" name="password" id="password" pattern="[0-9A-Za-z]{8}" placeholder="Ingrese la Contraseña" title="La contraseña debe tener numeros y letras (8)">
             <br><br>

             <select name="id_rol">
                <option value ="">Seleccione el tipo de Usuario</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from roles where id_rol =3");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_rol'] . ">"
                     . $fila['rol'] . "</option>";
                } 
                ?>
            </select>

            <select name="id_estado">
                <option value ="">Seleccione el Estado del Medico</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from estados where id_estado =3 OR id_estado =4");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_estado'] . ">"
                     . $fila['estado'] . "</option>";
                } 
                ?>
            </select>

            <select name="id_esp">
                <option value ="">Seleccione la especialización del Medico</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from especializacion");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_esp'] . ">"
                     . $fila['especializacion'] . "</option>";
                } 
                ?>
            </select>

             <input type="submit" name="inicio" value="Crear Medico">
            <input type="hidden" name="MM_insert" value="formreg">
            </form>
    </div>
              
</body>
</html>