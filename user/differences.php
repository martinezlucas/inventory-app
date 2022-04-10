<?php

    require '../server/connection.php';
    require '../server/validate.php';

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

    if($_SERVER['REQUEST_METHOD'] == 'POST') {        
        
        $validate = new Validate();
        $code = htmlspecialchars_decode($validate->input($_POST['code']));            

        $error = null;
        
        if(empty($code)) {

            $error = "Se ha ingresado una cadena vacía";

        } else {

            $code_count = $connection->check_code($code);

            if($code_count == 0) {

                $error = "No se encuentra el código: " . $code;
                
            } else {

                $code_data = $connection->get_code_data($code);
            }
        }        
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diferencias del conteo</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header">

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search hidden-flex">
            <input type="text" name="code" id="code" placeholder="Buscar código" required>            
            <input type="submit" name="search" value="&#128269;">
        </form>

        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="../user/menu.php" class="navigation-option cl-black">Atrás</a>
                <a href="../server/download_differences.php" class="navigation-option cl-black">Descargar tabla</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <h1 class="center-text hidden-block">Diferencias del conteo</h1>
        <br>        
        <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>                       
        <table class="hidden-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Almacén</th>
                    <th>Comprometido</th>
                    <th>Stock actual</th>
                    <th>U. contadas</th>
                    <th>Diferencia</th>
                    <th>Subido</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <?php if(!empty($error)): ?>
                    <br>
                    <p class="center-text"><?php echo $error ?></p>
                    <br>
                <?php 
                    else: 

                        $sum_of_code = $connection->get_sum_of_code($code_data['codigo']);

                        if(!empty($sum_of_code)) {

                            $difference = floatval($sum_of_code) - (floatval($code_data['stock_actual']) - floatval($code_data['comprometido']));
                        } else {

                            $difference = "Sin contar";
                        }
                ?>
                    <td><?php echo $code_data['linea']; ?></td>
                    <td><?php echo $code_data['codigo']; ?></td>
                    <td style="width: 20rem;"><?php echo $code_data['descripcion']; ?></td>
                    <td><?php echo $code_data['tipo']; ?></td>
                    <td><?php echo $code_data['marca']; ?></td>
                    <td><?php echo $code_data['referencia']; ?></td>
                    <td><?php echo $code_data['almacen']; ?></td>
                    <td><?php echo $code_data['comprometido']; ?></td>
                    <td><?php echo $code_data['stock_actual']; ?></td>
                    <td class="bg-lightblue"><?php echo $sum_of_code; ?></td>
                    <td><?php echo $difference; ?></td>
                    <td><?php echo $code_data['registrado']; ?></td>
                    <td><a href="count_details.php?code=<?php echo urlencode($code_data['codigo']); ?>&page=table" rel="noreferrer noopener" class="button-table">Detalles conteo</a></td>

                <?php endif; ?>
            <?php else: ?>
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
            <?php endif; ?>
        </table> 
        <br>
        <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>    
            <div class="hidden-flex">
                <a href="differences.php" rel="noreferrer noopener" class="button border cl-black">Reiniciar</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>