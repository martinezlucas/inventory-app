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

    $user_id = $_SESSION['user_id'];
    $connection = new Connection();
    $codes_added = $connection->get_table('producto_agregado');
    $codes_count = $connection->get_count_by_table('producto_agregado');

    /* Paginación */
    $pagination = new Pagination();
    $rows_per_page = 10;
    $pagination->set_rows_per_page($rows_per_page);
    $pagination->set_total_rows($codes_count);
    $pagination->set_buttons_hidden(true);
    $pagination->set_pagination();
    $index = $pagination->get_index();

    $codes_per_page = $connection->get_rows_per_page($index, $rows_per_page, "producto_agregado", "id");

    if($_SERVER['REQUEST_METHOD'] == 'POST') {        
        
        $validate = new Validate();
        $code = htmlspecialchars_decode($validate->input($_POST['code']));            

        $error = null;
        
        if(empty($code)) {

            $error = "Se ha ingresado una cadena vacía";

        } else {

            $code_data = $connection->get_added_count($code);

            if(empty($code_data)) {

                $error = "No se encuentra el código: " . $code;                
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
    <title>Códigos agregados general</title>
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
                <a href="menu.php" class="navigation-option cl-black">Atrás</a>
                <a href="../server/download_codes_added.php" class="navigation-option cl-black">Descargar tabla</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
               
       <h1 class="center-text hidden-block">Códigos agregados general</h1>
       <br>
       <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search hidden-flex">
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
               <th class="column-title">Descripción</th>
               <th class="column-title">Ubicación</th>
               <th class="column-title">Contado por</th>
               <th class="column-title">Fecha conteo</th>
               <th class="column-title">Modificado por</th>
               <th class="column-title">Fecha modificación</th>
           </tr>

            <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>

                <?php if(!empty($error)): ?>
                    <br>
                    <p class="center-text"><?php echo $error ?></p>
                    <br>
                <?php else: 
                    while($row = $code_data->fetch_assoc()): 
                        $user_name = $connection->get_user_name($row['id_usuario']);

                        if(empty($row['modif_por'])) {
                            $modified_by = "";
                        } else {
                            $modified_by = $connection->get_user_name($row['modif_por']);
                        }
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['codigo']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td style="width: 20rem;"><?php echo $row['descripcion']; ?></td>
                        <td style="width: 20rem;"><?php echo $row['ubicacion']; ?></td>
                        <td><?php echo $user_name; ?></td>
                        <td><?php echo $row['registrado']; ?></td>
                        <td><?php echo $modified_by; ?></td>
                        <td><?php echo $row['modificado']; ?></td>
                        <td><a href="modify_add.php?id=<?php echo $row['id']; ?>&page=codes_added" class="button-table">Modificar</a></td>
                        <td><button id="delete-button" onclick="deleteAdd(<?php echo $row['id']; ?>, 'codes_added')">Eliminar</button></td>
                    </tr>

                    <?php 
                        endwhile; 
                        $code_data->free();
                        $connection->close();
                    ?>
                    
                <?php endif; ?>    

            <?php else: ?> 
                <?php 
                    while($row = $codes_per_page->fetch_assoc()): 
                        $user_name = $connection->get_user_name($row['id_usuario']);

                        if(empty($row['modif_por'])) {
                            $modified_by = "";
                        } else {
                            $modified_by = $connection->get_user_name($row['modif_por']);
                        }
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td style="width: 20rem;"><?php echo $row['descripcion']; ?></td>
                    <td style="width: 20rem;"><?php echo $row['ubicacion']; ?></td>
                    <td><?php echo $user_name; ?></td>
                    <td><?php echo $row['registrado']; ?></td>
                    <td><?php echo $modified_by; ?></td>
                    <td><?php echo $row['modificado']; ?></td>
                    <td><a href="modify_add.php?id=<?php echo $row['id']; ?>&page=codes_added" class="button-table">Modificar</a></td>
                    <td><button id="delete-button" onclick="deleteAdd(<?php echo $row['id']; ?>, 'codes_added')">Eliminar</button></td>
                </tr>

                <?php 
                    endwhile; 
                    $codes_per_page->free();
                    $connection->close();
                ?>
            <?php endif; ?>    
       </table> 
       <br>
       <?php $pagination->show_buttons(); ?>

       <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>    
            <div class="hidden-flex">
                <a href="codes_added.php" rel="noreferrer noopener" class="button border cl-black">Reiniciar</a>
            </div>
        <?php endif; ?>
    </main>
    <script src="../js/confirm.js"></script>
</body>
</html>