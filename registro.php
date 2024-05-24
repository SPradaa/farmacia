<?php

   require_once ("db/connection.php");
   $db = new Database();
   $con = $db ->conectar();
   session_start();
?>

<?php

   if ((isset($_POST["MM_insert"]))&&($_POST["MM_insert"]=="formreg"))
   {
      $documento= $_POST['documento'];
      $id_doc= $_POST['id_doc'];
      $nombre= $_POST['nombre'];
      $apellido= $_POST['apellido'];
      $nit= $_POST['nit'];
      $id_rh= $_POST['id_rh'];
      $telefono= $_POST['telefono'];
      $correo= $_POST['correo'];
      $ciudad= $_POST['id_municipio'];
      $direccion=$_POST['direccion'];
      $clave= $_POST['password'];
      $id_rol= 5;
      $estado= 4;

      $sql= $con -> prepare ("SELECT * FROM usuarios WHERE documento='$documento'");
      $sql -> execute();
      $fila = $sql -> fetchAll(PDO::FETCH_ASSOC);
 
      if ($fila){
         echo '<script>alert ("EL DOCUMENTO YA EXISTE //CAMBIELO//");</script>';
         echo '<script>window.location="registro.php"</script>';
      }
      else
   
     if ($documento=="" || $id_doc=="" || $nombre=="" || $apellido=="" || $id_rh=="" || $telefono=="" || $correo=="" || $ciudad=="" || $direccion=="" || $clave=="" || $id_rol=="")
      {
         echo '<script>alert ("EXISTEN DATOS VACIOS");</script>';
         echo '<script>window.location="registro.php"</script>';
      }
      else
      {
        $pass_cifrado=password_hash($clave,PASSWORD_DEFAULT,array("pass"=>12));
        $insertSQL = $con->prepare("INSERT INTO usuarios(documento, id_doc, nombre, apellido, id_rh, telefono, correo, id_municipio, direccion, password, id_rol, id_estado, nit) VALUES('$documento', '$id_doc', '$nombre', '$apellido',  '$id_rh', '$telefono', '$correo', '$ciudad', '$direccion', '$pass_cifrado', '$id_rol', '$estado', '$nit')");
        $insertSQL -> execute();
        echo '<script> alert("REGISTRO EXITOSO");</script>';
        echo '<script>window.location="login.html"</script>';
        
    } 
}
    ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuario</title>
    <link rel="stylesheet" href="assets/css/registroo.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- Añade jQuery -->
</head>
</head>
<body>

<div class="regresar">
    <button onclick="goBack()" class="return">
        <span class="btxt">Regresar</span><i class="animate"></i>
    </button>
        
</div>

    <div class="login-box">
        <img src="assets/img/log.farma.png">
        <h1>REGISTRO USUARIO</h1>

        <form method="post" name="form1" id="form1"  autocomplete="off"> 

        <div class="row">
        <select name="id_doc">
                <option value ="">Seleccione el Tipo de Documento</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from t_documento ORDER BY tipo ASC");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_doc'] . ">"
                     . $fila['tipo'] . "</option>";
                } 
                ?>
            </select>
        
            <input type="text" name="documento" id="documento" pattern="[0-9]{8,11}" placeholder="Digite su Documento" title="El documento debe tener solo números de 8 a 10 dígitos" required>
            </div>

            <div class="row">
            <input type="text" name="nombre" id="nombre" pattern="[a-zA-ZÑ-ñ´ ]{3,30}" placeholder="Ingrese su Nombre" title="El nombre debe tener solo letras" required>

            <input type="text" name="apellido" id="apellido" pattern="[a-zA-ZÑ-ñ´ ]{4,30}" placeholder="Ingrese su Apellido" title="El apellido debe tener solo letras">
            </div>

            <div class="row">
            <select name="nit">
                <option value ="">Seleccione el Tipo de EPS</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from empresas ORDER BY empresa ASC");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['nit'] . ">"
                     . $fila['empresa'] . "</option>";
                } 
                ?>
            </select>

            <select name="id_rh">
                <option value ="">Seleccione el Tipo de Sangre</option>
                
                <?php
                    $control = $con -> prepare ("SELECT * from rh ORDER BY rh ASC");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['id_rh'] . ">"
                     . $fila['rh'] . "</option>";
                } 
                ?>
            </select></div>

            <div class="row">
            <input type="text" name="telefono" id="telefono" pattern="[0-9]{10}" placeholder="Ingrese su Telefono" title="El telefono debe tener solo numeros (10 digitos)">
            <input type="text" name="correo" id="correo" pattern="[0-9a-zA-Z.@_ ]{7,60}" placeholder="Ingrese su Correo" title="El correo debe ser alfanúmerico y tener caracteres especiales incluyendo el @">
            </div>

            <div class="row">
            <select name="id_departamento" id="id_depart">
                <option value="">Seleccione el Departamento</option>
                <?php
                    $control = $con->prepare("SELECT * FROM departamentos ORDER BY depart ASC");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $fila['id_depart'] . "'>" . $fila['depart'] . "</option>";
                    }
                ?>
            </select>
            <select name="id_municipio" id="id_municipio">
                <option value="">Seleccione el Municipio</option>
            </select>
        </div>

            <div class="row">
            <input type="text" name="direccion" id="direccion" pattern="[a-zA-Z0-9#.-_´ ]{5,40}" placeholder="Ingrese la dirección" title="La dirección debe tener minimo 7 caracteres">
            
             <input type="password" name="password" id="password" pattern="[0-9A-Za-zÑ-ñ]{4,15}" placeholder="Ingrese la Contraseña" title="La contraseña puede tener numeros o letras minimo 4 caracteres">
            </div>
             <br><br>

            
             <input type="submit" name="validar" value="Registrarme">
            <input type="hidden" name="MM_insert" value="formreg">
            </form>
    </div>

    <script>
    function goBack() {
        window.location.href = 'login.html';
    }

    $(document).ready(function(){
        $('#id_depart').change(function(){
            var id_depart = $(this).val();
            $.ajax({
                type: "POST",
                url: "municipio.php",
                data: {id_depart: id_depart},
                success: function(response){
                    $('#id_municipio').html(response);
                }
            });
        });
    });

    // Guardar los valores de los campos en el Local Storage antes de redirigir
    $(document).on('submit', '#form1', function(){
        var formValues = $(this).serializeArray();
        localStorage.setItem('formValues', JSON.stringify(formValues));
    });

    // Cargar los valores guardados del Local Storage cuando la página se carga
    $(document).ready(function(){
        var formValues = JSON.parse(localStorage.getItem('formValues'));
        if(formValues){
            $.each(formValues, function(index, element){
                $('[name="'+element.name+'"]').val(element.value);
            });
            localStorage.removeItem('formValues');
        }
    });
</script>

<script>
    // Función de validación de correo electrónico
    function validarCorreo() {
        var correo = document.getElementById("correo").value;
        if (correo.indexOf("@") == -1) {
            alert("El correo electrónico debe contener '@'");
            return false; // Detener el envío del formulario
        }
        return true; // Permitir el envío del formulario
    }

    // Asigna la función de validación al evento 'submit' del formulario
    document.getElementById("form1").onsubmit = validarCorreo;
</script>
              
</body>
</html>