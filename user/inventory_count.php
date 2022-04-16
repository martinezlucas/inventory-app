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
    $inventory_count = $connection->get_inventory_count();// tabla de inventario    

    /* Paginación */
    $pagination = new Pagination();
    $rows_per_page = 10;
    $pagination->set_rows_per_page($rows_per_page);
    $pagination->set_total_rows($inventory_count);
    $pagination->set_buttons_hidden(true);
    $pagination->set_pagination();
    $index = $pagination->get_index();

    $inventory_per_page = $connection->get_rows_per_page($index, $rows_per_page, "inventario", "id");

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

                $code_counted = $connection->get_count_by_code($code);
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
    <title>Conteo general</title>
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
                <a href="menu.php" rel="noreferrer noopener" class="navigation-option cl-black">Atrás</a>
                <a href="../server/download_count.php" rel="noreferrer noopener" class="navigation-option cl-black">Descargar tabla</a>
                <a href="../server/logout.php" rel="noreferrer noopener" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>               
       <h1 class="center-text hidden-block">Conteo general</h1>       
        <br>
       <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>

       <table class="hidden-table">           
           <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Cantidad</th>
                    <th>Ubicación</th>
                    <th>Contado por</th>
                    <th>Fecha conteo</th>
                    <th>Modificado por</th>
                    <th>Fecha modificación</th>
                    <th colspan="2">Opciones</th>
                </tr>
           </thead>

           <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <?php if(!empty($error)): ?>
                    <br>
                    <p class="center-text"><?php echo $error ?></p>
                    <br>

                <?php else: ?>
                    <tbody>                    
                        <?php 
                            while($row = $code_counted->fetch_assoc()): 
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
                            <td><a href="modify_count.php?id=<?php echo urlencode($row['id']); ?>&page=inventory" class="button-table">Modificar</a></td>
                            <td><button id="delete-button" onclick="deleteCount(<?php echo $row['id']; ?>, '<?php echo $row['codigo_producto']; ?>', 'inventory')">Eliminar</button></td>                            
                        </tr>

                        <?php 
                            endwhile; 
                            $code_counted->free();
                            $connection->close();
                        ?>
                    </tbody>
                <?php endif; ?>
           <?php else: ?>
                <tbody>                
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
                        <td><a href="modify_count.php?id=<?php echo urlencode($row['id']); ?>&page=inventory" class="button-table">Modificar</a></td>
                        <td><button id="delete-button" onclick="deleteCount(<?php echo $row['id']; ?>, '<?php echo $row['codigo_producto']; ?>', 'inventory')">Eliminar</button></td>
                    </tr>
                    <?php 
                        endwhile; 
                        $inventory_per_page->free();
                        $connection->close();
                    ?>
                </tbody>
            <?php endif; ?>
       </table> 

       <?php $pagination->show_buttons(); ?>
       <br>
        <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>    
            <div class="hidden-flex">
                <a href="inventory_count.php" rel="noreferrer noopener" class="button bg-button cl-white">Reiniciar</a>
            </div>
        <?php endif; ?>
    </main>
    <script src="../js/confirm.js"></script>
</body>
</html>