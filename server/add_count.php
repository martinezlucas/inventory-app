<?php

    require "connection.php";

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {
        
        if(isset($_POST['add-count'])) {

            $connection = new Connection();
                        
            $user = $_SESSION['user_id'];
            $code = $connection->get_connection()->real_escape_string($_POST['code']);
            $quantity = $connection->get_connection()->real_escape_string($_POST['quantity']);
            $description = $connection->get_connection()->real_escape_string($_POST['description']);
            $location = $connection->get_connection()->real_escape_string($_POST['location']);

            $product_added = $connection->add_product($user, $code, $quantity, $description, $location);

            $connection->close();

            if($product_added > 0) {

                header('location:../user/code_saved.php');

            } else {

                header('location:../user/error_page.php');
            }

        } else {

            header('location:../user/count.php');
        }
    }
?>