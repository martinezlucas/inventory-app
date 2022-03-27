<?php

    require "connection.php";
    require 'validate.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        if(isset($_POST['locate'])) {

            $connection = new Connection();
            $validate = new Validate();
            
            //$user = $_SESSION['user_id'];
            $code = $validate->input($_POST['code']); 

            $code_located = $connection->set_location(strtoupper($code));

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