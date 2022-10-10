<?php 
session_start();
require "config/config.php";
include "config/common.php";

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location:login.php');
}


$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$result = $stmt->fetchAll();
?>

<?php include "header.php" ?>

<main id="main" class="main">



    <section class="section">
        <table class="table">
            <a href="product_add.php" class="btn btn-success">Add Products</a>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Category</th>
                    <th scope="col">Price</th>
                    <th scope="col">In Stock</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                if($result){ 
                    $i = 1;
                    foreach($result as $value) { 
                    $catStmt = $pdo->prepare("SELECT * FROM categories WHERE id=" . $value['category_id']);
                    $catStmt->execute();
                    $catResult = $catStmt->fetch();
                ?>
                <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <td><?php echo escape($value['name']) ?></td>
                    <td><?php echo escape($value['description']) ?></td>
                    <td><?php echo escape($catResult['name']) ?></td>
                    <td><?php echo escape($value['price']) ?></td>
                    <td><?php echo escape($value['quantity']) ?></td>
                    <td>
                        <a href="product_edit.php?id=<?php echo $value['id'] ?>" class="btn btn-primary">Edit</a>
                        <a href="product_delete.php?id=<?php echo $value['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?') ">Delete</a>
                    </td>

                </tr>
                <?php $i++; }} ?>
            </tbody>
        </table>

    </section>

    <div class="d-flex justify-content-end">
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</main><!-- End #main -->

<?php include "footer.php" ?>