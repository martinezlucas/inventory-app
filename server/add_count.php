<?php

    require "connection.php";
    require "validate.php";

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();

    } else {
        
        if(isset($_POST['add-count'])) {

            $connection = new Connection();
            $validate = new Validate();
                        
            $user = $_SESSION['user_id'];
            $code = htmlspecialchars_decode($validate->input($_POST['code']));
            $quantity = $validate->input($_POST['quantity']);
            $description = $validate->input($_POST['description']);
            $location = $validate->input($_POST['location']);

            $product_added = $connection->add_product($user, $code, $quantity, $description, strtoupper($location));

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