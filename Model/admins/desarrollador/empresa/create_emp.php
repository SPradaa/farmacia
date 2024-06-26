<?php

   require_once ("../../../../db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
//   session_start();
?>
<?php
require_once("../../../../controller/seg.php");
validarSesion();
?>


<?php

   if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
   {
      $nit= $_POST['nit'];
      $empresa= $_POST['noempresa'];
      $code= $_POST['code'];
      $licencia= $_POST['licencia'];
      $inicio= $_POST['inicio'];
      $fin= $_POST['fin'];
      $estado= $_POST['estados']; 
      

      $sql= $con -> prepare ("SELECT * FROM empresas WHERE nit='$nit'");
      $sql -> execute();
      $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
 
      if ($fila){
         echo '<script>alert ("El NIT DE LA EMPRESA YA EXISTE CAMBIELO");</script>';
         echo '<script>window.location="create_emp.php"</script>';
      }

      $sql= $con -> prepare ("SELECT * FROM empresas WHERE empresa='$empresa'");
      $sql -> execute();
      $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
 
      if ($fila){
         echo '<script>alert ("ESTA EMPRESA YA EXISTE CAMBIELA);</script>';
         echo '<script>window.location="create_emp.php"</script>';
      }
      else
   
     if ($nit=="" || $empresa=="" || $code=="" ||$licencia=="")
      {
         echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
         echo '<script>window.location="create_emp.php"</script>';
      }
      else
      {
        // $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
        $insertSQL = $con->prepare("INSERT INTO empresas(nit, empresa, licencia, inicio, fin, codigo_unico, id_estado) VALUES('$nit', '$empresa', '$licencia', '$inicio', '$fin', '$code', '$estado')");
        $insertSQL -> execute();
        echo '<script> alert("REGISTRO EXITOSO");</script>';
        echo '<script>window.location="index_emp.php"</script>';
        
    } 
}
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empresas</title>
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="../css/registroem.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- Añade jQuery -->
    <style>
        @media (max-width: 768px) {
    .login-box {
        width: 460px;
        margin-top: -25px;
    }

    h1 {
        font-size: 22px; /* Reducir el tamaño del título en dispositivos más pequeños */
        margin-bottom: 35px; /* Reducir el espacio inferior del título */
        margin-top: -70px;
    }

    img {
        margin-left: 5px; /* Ajustar el margen izquierdo de la imagen en dispositivos móviles */
        margin-bottom: 20px;
    }

    .regresar {
        margin-top: 5px; /* Ajustar el margen superior de la sección de regreso */
        margin-bottom: 25px; /* Reducir el margen inferior de la sección de regreso */
    }
    label{
    margin-left: 8px;
}
    p{
        margin-left: 8px;
    }

    input[type="text"],
    input[type="date"],
    select {
        width: calc(48.5% - 10px);
        margin-left: 8px; /* Ajustar el margen izquierdo de los campos de entrada */
    }

    input[type="submit"] {
        margin-left: 75px; /* Ajustar el margen izquierdo del botón de submit */
        margin-bottom: 10px; /* Reducir el margen inferior del botón de submit */
        margin-top: 1px; /* Ajustar el margen superior del botón de submit */
    }
    .generate{
        margin-left:300px;
        width: 100px;
        margin-top: -90%;
        msrgin-bottom: -100px;
    }
}
</style>
    <script>
        function validateField(regex, input, errorMessage) {
            const value = input.value;
            const isValid = regex.test(value);
            input.setCustomValidity(isValid ? "" : errorMessage);
            input.reportValidity();
            return isValid;
        }

        $(document).ready(function() {
            $("#nit").on("input", function() {
                validateField(/^\d{8,10}$/, this, "Debe ingresar solo números (8 a 10 dígitos)");
            });

            $("#empresa").on("input", function() {
                validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s]){4,30}$/,  this, "Ingrese un nombre válido (solo letras)");
            });

            $("#code").on("input", function() {
                validateField(/^\d{3}$/, this, "Debe ingresar solo números (3 digitos)");
            });
        });

        function validateForm() {
            const isNitValid = validateField(/^\d{8,10}$/, document.getElementById("nit"), "Debe ingresar solo números (8 a 10 dígitos)");
            const isEmpresaValid = validateField(/^([a-zA-ZáéíóúÁÉÍÓÚñÑ\s])$/, document.getElementById("empresa"), "Ingrese un nombre válido (solo letras)");
            const isCodeValid = validateField(/^\d{3}$/, document.getElementById("code"), "Debe ingresar solo números (3 digitos)");

            return isNitValid && isEmpresaValid && isCodeValid;
        }
    </script>
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
        <h1>Crear Empresa</h1>
        <p>Ingrese los siguientes datos:</p>

        <form method="post" name="form1" id="form1" autocomplete="off">
        <div class="row">
                    <input type="text" name="nit" id="nit"  placeholder="Digite el NIT de la empresa" required>
                    
                    <input type="text" name="noempresa" id="empresa" placeholder="Ingrese el nombre de la empresa" required>
            </div>
            <div class="row">
                    <label for="licencia">Licencia:</label>
                    <input type="text" name="licencia" id="licencia" readonly>
                    <button type="button" onclick="generate()" required>Generar Licencia</button>
            </div>
            <div class="row">
            <label for="inicio">Fecha inicio licencia:</label>
<input type="date" name="inicio" id="inicio" value="<?php echo date('Y-m-d'); ?>" required readonly>

<script>
// Obtener la fecha del sistema en formato YYYY-MM-DD
var fechaSistema = new Date().toISOString().slice(0, 10);

// Establecer el atributo min del campo de fecha
document.getElementById("inicio").setAttribute("min", fechaSistema);
</script>

                    <label for="fin" class="fin">Fecha fin licencia:</label>
                    <?php
                        $fechaInicio = date('Y-m-d');
                        $fechaFin = date('Y-m-d', strtotime('+1 year', strtotime($fechaInicio)));
                        echo '<input type="date" name="fin" id="fin" value="' . $fechaFin . '" required readonly>';
                    ?>
                    </div>
                    <div class="row">
                    <input type="text" name="code" id="code" pattern="[0-9 ]{3}" placeholder="Ingrese el código único de la empresa" required>
               
                <select name="estados" id="estados" required>
                    <option value="">Seleccione el estado de la empresa</option>
                    <?php
                        $control = $con->prepare("SELECT * FROM estados WHERE id_estado IN (3, 4)");
                        $control->execute();
                        while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $fila['id_estado'] . '">' . $fila['estado'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" name="validar" value="Registrar Empresa">
                <input type="hidden" name="MM_insert" value="formreg">
            </div>
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
