<?php 
session_start();
require "config/config.php";

if(isset($_POST['submit'])){
    $id = $_POST['id'];
    $qty = $_POST['quantity'];
    

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
    $stmt->execute();
    $result = $stmt->fetch();

    if($qty > $result['quantity']){
        echo "<script>alert('Not enough stock');window.location.href='product_detail.php?id=$id'</script>";
    }elseif($qty <= 0){
        echo "<script>alert('Quantity must be at least one');window.location.href='product_detail.php?id=$id'</script>";
    }else{
        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
        }else{
            $_SESSION['cart']['id'.$id] = $qty;
        }
        header('location:cart.php');
    }
        
    }


if(isset($_GET['cid'])){

    $id = $_GET['cid'];
    $qty = 1;

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
    $stmt->execute();
    $result = $stmt->fetch();

    

    if($qty > $result['quantity']){
        echo "<script>alert('Not enough stock');window.location.href='shop.php'</script>";
    }else{
        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
        }else{
            $_SESSION['cart']['id'.$id] = $qty;
        }
        header('location:cart.php');
    }
}

if(isset($_POST['update_submit'])){
    $id = $_POST['id'];
    $qty = $_POST['quantity'];
   
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
    $stmt->execute();
    $result = $stmt->fetch();

    if($qty > $result['quantity']){
        echo "<script>alert('Not enough stock');window.location.href='cart.php'</script>";
    }elseif($qty <= 0){
        echo "<script>alert('Quantity must be at least one');window.location.href='cart.php'</script>";
    }else{
        unset($_SESSION['cart']['id'.$id]);
        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
        }else{
            $_SESSION['cart']['id'.$id] = $qty;
        }
        header('location:cart.php');
    }
}

// if(isset($_POST['update_submit'])){
//     $id = $_POST['id'];
//     $qty = $_POST['quantity'];

//     $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
//     $stmt->execute();
//     $result = $stmt->fetch();

//     $update_qty = $_SESSION['cart']['id'.$id] + $qty;

//     if($update_qty > $result['quantity']){
//         echo "<script>alert('Not enough stock');window.location.href='cart.php'</script>";
//     }elseif($update_qty <= 0){
//         echo "<script>alert('Quantity must be at least one');window.location.href='cart.php'</script>";
//     }else{
//         $_SESSION['cart']['id'.$id] = $update_qty;
//         header('location:cart.php');
//     }

// }
