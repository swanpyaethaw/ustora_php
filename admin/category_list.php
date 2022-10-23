<?php 
session_start();
require "config/config.php";
include "config/common.php";

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location:login.php');
}

if($_SESSION['user_role'] != 1){
    header('location:login.php');
}

if(isset($_POST['search'])){
    setcookie('search',$_POST['search'],time()+3600,'/');
}else{   
    if(empty($_GET['pageno'])){
    setcookie('search','',time()-3600,'/');
    }
}




?>

<?php include "header.php" ?>

<main id="main" class="main">

<?php 

    

    if(isset($_GET['pageno'])){
        $pageno = $_GET['pageno'];
    }else{
        $pageno = 1;
    }

    $num_of_rec = 3;
    $offset = ($pageno-1) * $num_of_rec;

    if(empty($_POST['search']) && empty($_COOKIE['search'])){
        $rawStmt = $pdo->prepare("SELECT * FROM categories  ORDER BY id DESC");
        $rawStmt->execute();
        $rawResult = $rawStmt->fetchAll();

        $total_pages = ceil(count($rawResult)/$num_of_rec);

        $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$num_of_rec");
        $stmt->execute();
        $result = $stmt->fetchAll();
    }else{
        $search = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
        $rawStmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$search%'  ORDER BY id DESC");
        $rawStmt->execute();
        $rawResult = $rawStmt->fetchAll();

        $total_pages = ceil(count($rawResult)/$num_of_rec);

        $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$num_of_rec");
        $stmt->execute();
        $result = $stmt->fetchAll();

    }
    




?>

    <section class="section">
        <table class="table">
            <a href="category_add.php" class="btn btn-success">Add Categories</a>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                if($result){ 
                    $i = 1;
                    foreach($result as $value) { 
                ?>
                <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <td><?php echo escape($value['name']) ?></td>
                    <td><?php echo escape($value['description']) ?></td>
                    <td>
                        <a href="category_edit.php?id=<?php echo $value['id'] ?>" class="btn btn-primary">Edit</a>
                        <a href="category_delete.php?id=<?php echo $value['id'] ?>" class="btn btn-danger"
                            onclick="return confirm('Are you sure want to delete?') ">Delete</a>
                    </td>

                </tr>
                <?php $i++; }} ?>
            </tbody>
        </table>

    </section>
    <!-- Basic Pagination -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <nav aria-label="Page navigation example ">
                    <ul class="pagination">
                        
                       <?php 
                       if($pageno>1){
                         echo '<li class="page-item "><a class="page-link" href="?pageno='.($pageno-1).'">Previous</a></li>';   
                    }
                       for($i=1;$i<=$total_pages;$i++){
                        if($i==$pageno){
                            echo '<li class="page-item"><a class="page-link active" href="?pageno='.$i.'">'.$i.'</a></li>';
                        }else{
                            echo '<li class="page-item"><a class="page-link " href="?pageno='.$i.'">'.$i.'</a></li>';
                        }
                            
                       }

                       if($pageno<$total_pages){
                        echo '<li class="page-item "><a class="page-link" href="?pageno='.($pageno+1).'">Next</a></li>';
                       }

                       ?>
                        
                    </ul>
                </nav>
            </div>
        </div>

    </div>
    <!-- End Basic Pagination -->
    <div class="d-flex justify-content-end">
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</main><!-- End #main -->

<?php include "footer.php" ?>


                        