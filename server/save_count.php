<?php

    require "connection.php";

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        if(isset($_POST['count'])) {

            $connection = new Connection();
            
            $user = $_SESSION['user_id'];
            $code = $connection->get_connection()->real_escape_string($_POST['send-code']);            
            $quantity = $connection->get_connection()->real_escape_string($_POST['quantity']);
            $page = $_POST['page'];
            $location = $_POST['location'];

            $count_saved = $connection->save_count($user, $code, $quantity, $location);

            $connection->close();

            if($count_saved > 0) {

                if($page == "differences") {

                    header('location:../user/differences.php');

                } else {

                    header('location:../user/code_saved.php');
                }

            } else {

                header('location:../user/error_page.php');
            }

        } else {

            header('location:../user/count.php');
        }
    }
?>