<?php 
require "config/config.php";


$stmt = $pdo->prepare("SELECT * FROM product_review WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetch();


$pStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$result['product_id']);
$pStmt->execute();
$pResult = $pStmt->fetch();

$stmt = $pdo->prepare("DELETE FROM product_review WHERE id=".$_GET['id']);
$result = $stmt->execute();

if($result){
    header("location:product_detail.php?id=".$pResult['id']);
}
