<?php

    require '../server/connection.php';

    session_start();
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
            die();
        }

    } else {

        header('location:../');
        die();
    }

    $connection = new Connection();
    $codes_count = $connection->get_number_of_codes();
    $codes = $connection->get_codes();
    $sum_of_all_codes = $connection->get_sum_of_all_codes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header">
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="../user/menu.php" class="navigation-option">Menú principal</a>
                <a href="load_data.php" class="navigation-option">Nuevo inventario</a>
                <a href="../server/download_differences.php" class="navigation-option">Descargar tabla</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <h1 class="center-text hidden-block">Diferencias del conteo</h1>
        <br>       
        <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>
        <table class="table hidden-table">
            <tr>
                <th class="column-title">ID</th>
                <th class="column-title">Código</th>
                <th class="column-title">Descripción</th>
                <th class="column-title">Tipo</th>
                <th class="column-title">Marca</th>
                <th class="column-title">Almacén</th>
                <th class="column-title">Comprometido</th>
                <th class="column-title">Stock actual</th>
                <th class="column-title">U. contadas</th>
                <th class="column-title">Diferencia</th>
                <th class="column-title">Subido</th>
            </tr>

            <?php
                while($row = $codes->fetch_assoc()):

                    $sum_of_code = $connection->get_sum_of_code($row['codigo']);
                    
                    if(!empty($sum_of_code)) {
                        $difference = floatval($sum_of_code) - (floatval($row['stock_actual']) - floatval($row['comprometido']));
                    } else {
                        $difference = "Sin contar";
                    }

                    if($difference != 0):
            ?>
            <tr>
                <td><?php echo $row['linea']; ?></td>
                <td><?php echo $row['codigo']; ?></td>
                <td style="width: 20rem;"><?php echo $row['descripcion']; ?></td>
                <td><?php echo $row['tipo']; ?></td>
                <td><?php echo $row['marca']; ?></td>
                <td><?php echo $row['almacen']; ?></td>
                <td><?php echo $row['comprometido']; ?></td>
                <td><?php echo $row['stock_actual']; ?></td>
                <td><?php echo $sum_of_code; ?></td>
                <td><?php echo $difference; ?></td>
                <td><?php echo $row['registrado']; ?></td>
                <?php if($sum_of_code == ""): ?>
                    <td><a href="count.php?code=<?php echo urlencode($row['codigo']); ?>&page=differences" class="button-table">Contar</a></td>
                <?php else: ?>
                    <td><a href="count_details.php?code=<?php echo urlencode($row['codigo']); ?>&page=differences" class="button-table">Detalles conteo</a></td>
                <?php endif; ?>
            </tr>
            <?php
                    endif;  
                endwhile; 
                $codes->free();
                $connection->close();
            ?>
        </table> 
    </main>
</body>
</html>