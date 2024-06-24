<?php
    session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();

    $sql = $con -> prepare ("SELECT * FROM usuarios, roles, t_documento, municipios, departamentos, empresas, rh, estados
    WHERE usuarios.id_doc = t_documento.id_doc AND usuarios.id_municipio = municipios.id_municipio
    AND municipios.id_depart = departamentos.id_depart 
    AND usuarios.nit = empresas.nit AND usuarios.id_rh = rh.id_rh AND usuarios.id_rol = roles.id_rol
    AND usuarios.id_estado = estados.id_estado AND usuarios.documento = '".$_GET['documento']."'");
    $sql -> execute();
    $usua =$sql -> fetch();
?>

<?php

if(isset($_POST["update"]))
 {
    $id_doc= $_POST['id_doc'];
    $documento= $_POST['documento'];
    $nombre= $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $nit= $_POST['nit'];
    $id_rh= $_POST['id_rh'];
    $telefono= $_POST['telefono'];
    $correo= $_POST['correo'];
    $id_rol= $_POST['id_rol'];
    $id_estado= $_POST['id_estado'];
 
   if ($documento=="" || $id_doc=="" || $nombre=="" || $apellido=="" || $nit=="" || $id_rh=="" || $telefono=="" || $correo=="" || $id_rol=="" || $id_estado=="")
    {
       echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
       echo '<script>window.location="index_usu.php"</script>';
    }
    else
    {
      $insertSQL = $con->prepare("UPDATE usuarios SET documento = '$documento', id_doc = '$id_doc', 
      nombre = '$nombre', apellido = '$apellido', nit = '$nit', id_rh = '$id_rh', telefono = '$telefono',
      correo = '$correo', id_rol = '$id_rol', id_estado = '$id_estado' WHERE documento = '".$_GET['documento']."'");
      $insertSQL -> execute();
      echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';
      echo '<script>window.location="index_usu.php"</script>';
      
  } 
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edi_usu.css">
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <title>Actualizar Usuario</title>
    <style>
        @media(max-width: 768px){
            .regresar{
                margin-top:30px;
            }
            input[type="submit"]{
                width: 170px;
                margin-left: 125px;
            }
            
        }
        </style>
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
            $("#documento").on("input", function() {
                validateField(/^\d{8,10}$/, this, "Debe ingresar solo números (8 a 10 dígitos)");
            });

            $("#nombre").on("input", function() {
                validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, this, "Ingrese un nombre válido (solo letras)");
            });

            $("#apellido").on("input", function() {
                validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, this, "Ingrese un apellido válido (solo letras)");
            });

            $("#telefono").on("input", function() {
                validateField(/^\d{10}$/, this, "Debe ingresar solo números (10 dígitos)");
            });

            $("#correo").on("input", function() {
                validateField(/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/, this, "Ingrese un correo válido que lleve '@'");
            });
        });

        function validateForm() {
            const isDocumentoValid = validateField(/^\d{8,10}$/, document.getElementById("documento"), "Debe ingresar solo números (8 a 10 dígitos)");
            const isNombreValid = validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, document.getElementById("nombre"), "Debe ingresar solo letras");
            const isApellidoValid = validateField(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, document.getElementById("apellido"), "Debe ingresar solo letras");
            const isTelefonoValid = validateField(/^\d{10}$/, document.getElementById("telefono"), "Debe ingresar solo números (10 dígitos)");
            const isCorreoValid = validateField(/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/, document.getElementById("correo"), "Debe ser un correo válido que lleve '@'");

            return isDocumentoValid && isNombreValid && isApellidoValid && isTelefonoValid && isCorreoValid;
        }
    </script>
</head>
<body>
<div class="regresar">
        <div class="col-md-6">
            <form action="index_usu.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
        </div>
    <div class="login-box">
    <img src="../../../../assets/img/log.farma.png">
        <h1>Editar Usuario</h1>
        <form method="POST" name="formreg" autocomplete="off">
            <div class="row">
                <select name="id_doc">
                    <option value="<?php echo $usua['id_doc']?>"><?php echo $usua['tipo']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM t_documento");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_doc'] . ">" . $fila['tipo'] . "</option>";
                        }
                    ?>
                </select>
                
                <input type="text" name="documento" id="documento" value="<?php echo $usua['documento']?>" readonly> 
            </div>
            <div class="row">
                <input type="text" name="nombre" id="nombre"  value="<?php echo $usua['nombre']?>" readonly>
                <input type="text" name="apellido" id="apellido" value="<?php echo $usua['apellido']?>" readonly>
            </div>
            <div class="row">
                <input type="text" name="telefono" id="telefono"  value="<?php echo $usua['telefono']?>">
                <input type="text" name="correo" id="correo" value="<?php echo $usua['correo']?>">
            </div>
            <div class="row">
                <select name="nit">
                    <option value="<?php echo $usua['nit']?>"><?php echo $usua['empresa']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM empresas");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['nit'] . ">" . $fila['empresa'] . "</option>";
                        }
                    ?>
                </select>
        <select name="id_rh" id="id_rh">
            <option value="<?php echo $usua['id_rh']?>"><?php echo $usua['rh']?></option>
            <?php
                $control = $con->prepare("SELECT * FROM rh");
                $control->execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=" . $fila['id_rh'] . ">" . $fila['rh'] . "</option>";
                }
            ?>
        </select>
            </div>
            <div class="row">
                <select name="id_rol">
                    <option value="<?php echo $usua['id_rol']?>"><?php echo $usua['rol']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM roles WHERE id_rol IN (2, 4, 5)");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $fila['id_rol'] . ">" . $fila['rol'] . "</option>";
                        }
                    ?>
                </select>
                <select name="id_estado">
                    <option value="<?php echo $usua['id_estado']?>"><?php echo $usua['estado']?></option>
                    <?php
                        $control = $con->prepare("SELECT * FROM estados WHERE id_estado IN (3, 4)");
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
    document.getElementById('id_rh').addEventListener('change', function() {
        var options = this.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value !== this.value) {
                options[i].disabled = true;
            }
        }
    });
</script>


</body>
</html>
