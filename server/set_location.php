<?php

    require 'connection.php';
    require 'validate.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();

    } else {

        if(isset($_POST['locate'])) {

            $connection = new Connection();
            $validate = new Validate();
            
            $user_id = $_SESSION['user_id'];
            $location = $validate->input($_POST['code']); 

            $code_located = $connection->update_location($location, $user_id);

            $connection->close();

            if($code_located > 0) {

                header('location:../user/search_product.php');

            } else {

                header('location:../user/error_page.php');
            }

        } else {

            header('location:../user/menu.php');
        }
    }
?>