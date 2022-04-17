<?php

    require '../server/connection.php';

    session_start();

    if (!isset($_SESSION['user_id'])) {

        header('location:../');
        die();

    } else {

        $code = $_GET['code'];
        $page = $_GET['page'];
        $connection = new Connection();
        $count_by_code = $connection->get_count_by_code($code);

        switch ($page) {
            case 'count':
                $back = 'count.php?code=' . urlencode($code) . '&page=search';
                break;

            case 'differences':
                $back = 'differences.php';
                break;

            case 'table':
                $back = 'codes_table.php';
                break;

            case 'inventory':
                $back = 'inventory_count.php';
                break;

            case 'search':
                $back = 'search_by_count.php';
                break;

            default:
                header('location:../');
                die();
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
    <header class="header-menu">
        <h1 class="cl-white no-margin">Conteos por código</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="<?php echo $back; ?>" class="navigation-option cl-black">Atrás</a>
                <a href="menu.php" class="navigation-option cl-black">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <br>
        <?php if($page != 'count') : ?>
            <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>
        <?php endif; ?>   

        <?php if(($page != 'count' && $page != 'search') && $_SESSION['user_rol'] == 1): ?>
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
                <tbody>                
                    <?php
                        while ($row = $count_by_code->fetch_assoc()) :
                            $user_name = $connection->get_user_name($row['id_usuario']);

                            if (!empty($row['modif_por'])) {
                                $modified_by = $connection->get_user_name($row['modif_por']);
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
                            <td><a href="modify_count.php?id=<?php echo urlencode($row['id']); ?>&page=<?php echo $page; ?>" class="button-table">Modificar</a></td>
                            <td><button id="delete-button" onclick="deleteCount(<?php echo $row['id']; ?>, '<?php echo $row['codigo_producto']; ?>', '<?php echo $page; ?>')">Eliminar</button></td>
                        </tr>

                    <?php
                        endwhile;
                        $count_by_code->free();                    
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <?php while ($row = $count_by_code->fetch_assoc()) : 
                    $user_name = $connection->get_user_name($row['id_usuario']);

                    if (!empty($row['modif_por'])) {
                        $modified_by = $connection->get_user_name($row['modif_por']);
                    } else {
                        $modified_by = "";
                    }
            ?>
                <div class="soft-border card">
                    <p>ID: <?php echo $row['id']; ?></p>
                    <br>
                    <p>Código: <?php echo $row['codigo_producto']; ?></p>
                    <br>
                    <p>Cantidad: <?php echo $row['cantidad']; ?></p>
                    <br>
                    <p>Ubicación: <?php echo $row['ubicacion']; ?></p>
                    <br>
                    <p>Contado por: <?php echo $user_name; ?></p>
                    <br>
                    <p>Fecha conteo: <?php echo $row['registrado']; ?></p>
                    <br>
                    <p>Modificado por: <?php echo $modified_by; ?></p>
                    <br>
                    <p>Fecha modificación: <?php echo $row['modificado']; ?></p>
                </div>
            <?php
                endwhile;
                $count_by_code->free();
            ?>
        <?php 
            endif; 
            $connection->close();
        ?>
    </main>
    <script src="../js/confirm.js"></script>
</body>

</html>