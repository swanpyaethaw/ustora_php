<?php 

session_start();
unset($_SESSION['user_name']);
unset($_SESSION['user_id']);
unset($_SESSION['user_role']);
unset($_SESSION['user_image']);
unset($_SESSION['logged_in']);
unset($_SESSION['cart']);

// session_destroy();




header('location:index.php');