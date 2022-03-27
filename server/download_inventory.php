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
    header('Content-Disposition: attachment; filename=inventario.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, array('ID', 
                           'Código', 
                           'Descripción', 
                           'Tipo', 
                           'Marca', 
                           'Referencia', 
                           'Almacén', 
                           'Comprometido', 
                           'Stock Actual', 
                           'Unidades contadas', 
                           'Diferencia'));
    
    $connection = new Connection();
    $codes = $connection->get_codes();

    while($row = $codes->fetch_assoc()) {

        $sum_of_code = $connection->get_sum_of_code($row['codigo']);
        
        if(!empty($sum_of_code)) {

            $difference = floatval($sum_of_code) - (floatval($row['stock_actual']) - floatval($row['comprometido']));

        } else {

            $difference = "Sin contar";
        }

        $array = array($row['linea'], 
                       $row['codigo'], 
                       stripslashes($row['descripcion']), 
                       $row['tipo'], 
                       $row['marca'], 
                       $row['referencia'], 
                       $row['almacen'],
                       $row['comprometido'],
                       $row['stock_actual'],
                       $sum_of_code,
                       $difference);

        fputcsv($output, $array);
    }
    
    fclose($output);
    $connection->close();
    $codes->free();
        
?>