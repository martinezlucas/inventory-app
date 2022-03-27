<?php

    require 'connection.php';

    session_start();
        
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
        }

    } else {

        header('location:../');
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=codigos_agregados.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, array('ID', 
                            'Código', 
                            'Cantidad', 
                            'Descripcion', 
                            'Ubicación',
                            'Contado por',
                            'Fecha conteo'));
    
    $connection = new Connection();
    $codes_added = $connection->get_codes_added();

    while($row = $codes_added->fetch_assoc()) {

        $user_name = $connection->get_user_name($row['id_usuario']);
        
        $array = array($row['id'], 
                        $row['codigo'], 
                        $row['cantidad'],
                        $row['descripcion'],
                        $row['ubicacion'], 
                        $user_name, 
                        $row['registrado']);

        fputcsv($output, $array);
    }
    
    fclose($output);        
    $codes_added->free();
    $connection->close();
    
?>