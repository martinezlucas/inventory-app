<?php

    require '../server/connection.php';
    require '../server/validate.php';
    require '../objects/pagination.php';

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
    $codes_count = $connection->get_number_of_codes();// tabla de productos    
    $sum_of_all_codes = $connection->get_sum_of_all_codes();// tabla de inventario    
    
    /* Paginación */
    $pagination = new Pagination();
    $rows_per_page = 10;
    $pagination->set_rows_per_page($rows_per_page);
    $pagination->set_total_rows($codes_count);
    $pagination->set_pagination();
    $index = $pagination->get_index();

    $codes_per_page = $connection->get_rows_per_page($index, $rows_per_page, "producto", "linea");

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
                <a href="../user/menu.php" rel="noreferrer noopener" class="navigation-option">Atrás</a>
                <a href="load_data.php" rel="noreferrer noopener" class="navigation-option">Nuevo inventario</a>
                <a href="../server/download_inventory.php" rel="noreferrer noopener" class="navigation-option">Descargar tabla</a>
                <a href="../server/logout.php" rel="noreferrer noopener" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>               
        <h1 class="center-text hidden-block">Códigos subidos a la base de datos</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search hidden-block">
            <input type="text" name="code" id="code" placeholder="Buscar código" required>            
            <input type="submit" name="search" value="&#128269;">
        </form>
        <br>
        <p class="center-text hidden-block">Códigos subidos: <?php echo $codes_count; ?>, códigos contados: <?php echo $sum_of_all_codes; ?>
            , porcentaje del conteo:

            <?php 
                if($codes_count == 0) {
                    echo "0.00%";
                } else {
                    echo number_format(($sum_of_all_codes * 100)/ $codes_count, 2) . "%";
                }
            ?>
        </p>
        <br>
       
       <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>

       <table class="table hidden-table">           
           <tr>
               <th class="column-title">ID</th>
               <th class="column-title">Código</th>
               <th class="column-title">Descripción</th>
               <th class="column-title">Tipo</th>
               <th class="column-title">Marca</th>
               <th class="column-title">Referencia</th>
               <th class="column-title">Almacén</th>
               <th class="column-title">Comprometido</th>
               <th class="column-title">Stock actual</th>
               <th class="column-title">U. contadas</th>
               <th class="column-title">Diferencia</th>
               <th class="column-title">Subido</th>
           </tr>

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
                    while($row = $codes_per_page->fetch_assoc()):
                        $sum_of_code = $connection->get_sum_of_code($row['codigo']);
                        
                        if(!empty($sum_of_code)) {

                            $difference = floatval($sum_of_code) - (floatval($row['stock_actual']) - floatval($row['comprometido']));
                        } else {

                            $difference = "Sin contar";
                        }
                ?>
                <tr>
                    <td><?php echo $row['linea']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td style="width: 20rem;"><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['tipo']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['referencia']; ?></td>
                    <td><?php echo $row['almacen']; ?></td>
                    <td><?php echo $row['comprometido']; ?></td>
                    <td><?php echo $row['stock_actual']; ?></td>
                    <td><?php echo $sum_of_code; ?></td>
                    <td><?php echo $difference; ?></td>
                    <td><?php echo $row['registrado']; ?></td>
                    <td><a href="count_details.php?code=<?php echo urlencode($row['codigo']); ?>&page=table" rel="noreferrer noopener" class="button-table">Detalles conteo</a></td>
                </tr>

                <?php 
                    endwhile; 
                    $codes_per_page->free();
                    $connection->close();
                ?>
           <?php endif; ?>
        </table> 
        
        <?php $pagination->show_buttons(); ?>

        <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>    
            <a href="codes_table.php" rel="noreferrer noopener" class="pagination button soft-border hidden-block">Reiniciar</a>
        <?php endif; ?>
    </main>
</body>
</html>