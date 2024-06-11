<?php
require_once("../../db/connection.php"); 
$db = new Database();
$con = $db->conectar();
session_start();

// Verificar si hay una sesión activa
if(isset($_SESSION['documento'])) {
    // Obtener el NIT del médico
    $nit_medico = $_SESSION['nit'];

    // Consultar datos de la empresa asociada al NIT del médico
    $consulta_empresa = $con->prepare("SELECT * FROM empresas WHERE nit = :nit");
    $consulta_empresa->bindParam(':nit', $nit_medico);
    $consulta_empresa->execute();
    $empresa = $consulta_empresa->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró una empresa asociada al NIT del médico
    if ($empresa) {
        $nombre_empresa = $empresa['empresa']; // Obtener el nombre de la empresa
        $codigoUnicoEmpresa = $empresa['codigo_unico']; // Obtener el código único de la empresa
    } else {
        // Si no se encuentra la empresa asociada al NIT del médico, asignar un valor por defecto
        $nombre_empresa = "Empresa no encontrada";
        $codigoUnicoEmpresa = ""; // Establecer código único vacío
    }
} else {
    // Si no hay sesión activa, asignar un valor por defecto
    $nombre_empresa = "Usuario no logueado";
    $codigoUnicoEmpresa = ""; // Establecer código único vacío
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Validación de Código</title>
    <!-- Agregar los estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ZyZ0AQv7g8v5uw3LmHTnG/EFt9BSADRnayJ+9ADxY2lR7EMsVfBSu8wb8l2ng6i/" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Ingrese el Código de Validación</h2>
                <h3>Nombre de la empresa: <?php echo $nombre_empresa; ?></h3>
                <form action="../../controller/validacion_medico.php" method="post">
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" id="codigo" name="codigo" class="form-control" required>
                    </div>
                    <!-- Campo oculto para enviar el código único de la empresa -->
                    <input type="hidden" name="codigo_unico_empresa" value="<?php echo $codigoUnicoEmpresa; ?>">
                    <button type="submit" name="inicio" class="btn btn-primary">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Agregar los scripts de Bootstrap (jQuery y Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-47ln0VE7uNUHRT3XJ7hlsZt+pf5+0N5xet+7vZzudvX1Ckq+ZgcUjWScZO5r/+Hb" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-lLjyZKJUdAVvyHkbz4H2E+D/ZKWCyYxSCbmX+3uULvhvZlF/Y3mRm3GsCxEttYen" crossorigin="anonymous"></script>
</body>
</html>
