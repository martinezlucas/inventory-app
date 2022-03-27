<?php

    require 'connection.php';

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

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=conteo_general.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, array('ID', 
                           'Código', 
                           'Cantidad',
                           'Ubicación', 
                           'Contado por', 
                           'Fecha conteo',
                           'Modificado por',
                           'Fecha modificación'));
    
    $connection = new Connection();
    $all_count = $connection->get_all_count();

    while($row = $all_count->fetch_assoc()) {

        $user_name = $connection->get_user_name($row['id_usuario']);

        if(!empty($row['modif_por'])) {

            $modified_by = $connection->get_user_name($row['id_usuario']);

        } else {

            $modified_by = "";
        }
        
        $array = array($row['id'], 
                       $row['codigo_producto'], 
                       $row['cantidad'],
                       $row['ubicacion'], 
                       $user_name, 
                       $row['registrado'],
                       $modified_by,
                       $row['modificado']);

        fputcsv($output, $array);
    }
    
    fclose($output);        
    $all_count->free();
    $connection->close();
?>