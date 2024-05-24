<?php
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-left: -112px;
            margin-right: -70px;
            margin-top: 40px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #e2e2e2;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-container .btn {
            width: 48%;
        }
        .text{
            margin-top: -30px;
            text-align: center;
            padding: 5px;
        }
        .espacios{
            margin-top: 20px;
            width: 100%;
            height: auto;
            /* background: red; */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;

        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text">Usuarios Registrados</h1>
    <br>
    <div class="text-center">
        <div class="row mt-3">
            <div class="col-md-6">
                <form action="../index.php">
                    <input type="submit" value="Regresar" class="btn btn-secondary"/>
                </form>
              
            </div>
            <div class="col-md-6">
                <a href="create_usu.php" class="btn btn-success"><i class="fas fa-user-plus"></i>Crear Usuario</a>
            </div>
            
        </div>
       
    </div>
    <div class="espacios">
    <div class="col-md-6">
            <form action="generar_pdf.php" method="post">
                <label for="">Generar Reportes  </label>
                <button type="submit" class="btn btn-danger"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</button>
            </form>
            </div>
    </div>
    
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Tipo_documento</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>EPS</th>
                <th>Tipo_sangre</th>
                <th>Tipo de Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $consulta = "SELECT * FROM usuarios, roles, t_documento, municipios, empresas, rh, estados
            WHERE usuarios.id_rol = roles.id_rol AND usuarios.id_doc = t_documento.id_doc AND usuarios.id_municipio = municipios.id_municipio AND
            usuarios.id_rh = rh.id_rh AND usuarios.id_estado = estados.id_estado AND usuarios.nit = empresas.nit";
            $resultado = $con->query($consulta);

            while ($fila = $resultado->fetch()) {
                echo '
                <tr>
                    <td>' . $fila["tipo"] . '</td>
                    <td>' . $fila["documento"] . '</td>
                    <td>' . $fila["nombre"] . '</td>
                    <td>' . $fila["apellido"] . '</td>
                    <td>' . $fila["telefono"] . '</td>
                    <td>' . $fila["correo"] . '</td>
                    <td>' . $fila["municipio"] . '</td>
                    <td>' . $fila["direccion"] . '</td>
                    <td>' . $fila["empresa"] . '</td>
                    <td>' . $fila["rh"] . '</td>
                    <td>' . $fila["rol"] . '</td>
                    <td>' . $fila["estado"] . '</td>
                    <td>
                        <div class="text-center">
                            <a href="update_usu.php?id=' . $fila['documento'] . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="delete_usu.php?id=' . $fila['documento'] . '" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>