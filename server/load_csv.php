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

    if(isset($_POST['upload-file'])) {
        if($_FILES['csv_file']['name']) {

            $filename = explode('.', $_FILES['csv_file']['name']);

            if(!end($filename) == 'csv') {

                header('location:../logs/file_format_error.php');
                die();

            } else {

                $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
                $connection = new Connection();
                $tables_clean = $connection->clean_tables();

                if($tables_clean == 0) {

                    header('location:../logs/clear_table_error.php');
                    die();

                } else {

                    $flag = false;
                    $validate = new Validate();

                    while($data = fgetcsv($handle)) {

                        $id = $validate->input($data[0]);
                        $code = htmlspecialchars_decode($validate->input($data[1]));
                        $description = htmlspecialchars_decode($validate->input($data[2]));
                        $type = $validate->input($data[3]);
                        $brand = $validate->input($data[4]);
                        $reference = $validate->input($data[5]);
                        $warehouse = $validate->input($data[6]);
                        $to_deliver = $validate->input($data[7]);
                        $actual_stock = $validate->input($data[8]);
                        $unit = $validate->input($data[9]);
                        $price = $validate->input($data[10]);
                        
                        if($flag) {
                            $connection->load_product(intval($id),
                                                      $code,
                                                      $description,
                                                      $type,
                                                      $brand,
                                                      $reference,
                                                      $warehouse,
                                                      floatval($to_deliver),
                                                      floatval($actual_stock),
                                                      $unit,
                                                      floatval($price));
                        } 

                        $flag = true;
                    }

                    fclose($handle);
                    $connection->close();
                    header('location:../user/codes_table.php');
                    die();
                }                                        
            }

        } else {
            header('location:../logs/file_not_loaded.php');
            die();
        }
    }    
?>