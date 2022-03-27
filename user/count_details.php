<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        $code = $_GET['code'];
        $page = $_GET['page'];
        $connection = new Connection();
        $count_by_code = $connection->get_count_by_code($code);

        if($page == 'count') {
            $back = 'count.php?code=' . urlencode($code) . '&page=search';
        } else if($page == 'differences') {
            $back = 'differences.php';
        } else {
            $back = 'codes_table.php';
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
        <nav>
            <a href="<?php echo $back; ?>" class="button bg-white">Atrás</a>
            <a href="../server/logout.php" class="button bg-white">Cerrar sesión</a>
        </nav>
    </header>

    <main>
               
       <h1 class="center-text">Registro de conteos por código</h1>

       <table class="table">           
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
                while($row = $count_by_code->fetch_assoc()): 
                    $user_name = $connection->get_user_name($row['id_usuario']);

                    if(!empty($row['modif_por'])) {
                        $modified_by = $connection->get_user_name($row['modif_por']);
                    } else {
                        $modified_by = "";
                    }

                    $product_code = $row['codigo_producto']; 
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $product_code; ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td><?php echo $row['ubicacion']; ?></td>
                <td><?php echo $user_name; ?></td>
                <td><?php echo $row['registrado']; ?></td>
                <td><?php echo $modified_by; ?></td>
                <td><?php echo $row['modificado']; ?></td>
                <td><a href="modify_count.php?id=<?php echo urlencode($row['id']); ?>&page=<?php echo $page; ?>" class="button-table">Modificar</a></td>
                <td><button id="delete-button" onclick="deleteCount(<?php echo $row['id']; ?>, '<?php echo $product_code; ?>')">Eliminar conteo</button></td>
            </tr>

            <?php 
                endwhile; 
                $count_by_code->free();
                $connection->close();
            ?>
       </table> 
    </main>
    <script src="../js/confirm.js"></script>
</body>
</html>