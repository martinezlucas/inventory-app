<?php

    require '../server/connection.php';
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
    $inventory_count = $connection->get_inventory_count();// tabla de inventario
    //$all_count = $connection->get_all_count();     

    /* Paginación */
    $pagination = new Pagination();
    $rows_per_page = 10;
    $pagination->set_rows_per_page($rows_per_page);
    $pagination->set_total_rows($inventory_count);
    $pagination->set_pagination();
    $index = $pagination->get_index();

    $inventory_per_page = $connection->get_inventory_per_page($index, $rows_per_page);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conteo general</title>
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
                <a href="menu.php" rel="noreferrer noopener" class="navigation-option">Atrás</a>
                <a href="../server/download_count.php" rel="noreferrer noopener" class="navigation-option">Descargar tabla</a>
                <a href="../server/logout.php" rel="noreferrer noopener" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>               
       <h1 class="center-text hidden-block">Conteo general</h1>
       <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search hidden-block">
            <input type="text" name="code" id="code" placeholder="Buscar código" required>            
            <input type="submit" name="search" value="&#128269;">
        </form>
        <br>
       <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>

       <table class="table hidden-table">           
           <tr>
               <th class="column-title">ID</th>
               <th class="column-title">Código</th>
               <th class="column-title">Cantidad</th>
               <th class="column-title">Ubicación</th>
               <th class="column-title">Contado por</th>
               <th class="column-title">Fecha conteo</th>
               <th class="column-title">Modificado por</th>
               <th class="column-title">Fecha modificación</th>
           </tr>

           <?php 
                while($row = $inventory_per_page->fetch_assoc()): 
                    $user_name = $connection->get_user_name($row['id_usuario']);

                    if(!empty($row['modif_por'])) {
                        $modified_by = $connection->get_user_name($row['id_usuario']);
                    } else {
                        $modified_by = "";
                    }
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['codigo_producto']; ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td><?php echo $row['ubicacion']; ?></td>
                <td><?php echo $user_name; ?></td>
                <td><?php echo $row['registrado']; ?></td>
                <td><?php echo $modified_by; ?></td>
                <td><?php echo $row['modificado']; ?></td>
            </tr>

            <?php 
                endwhile; 
                $inventory_per_page->free();
                $connection->close();
            ?>
       </table> 

       <?php $pagination->show_buttons(); ?>                

    </main>
</body>
</html>