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

    
        $rawStmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
        $rawStmt->execute();
        $rawResult = $rawStmt->fetchAll();

        $total_pages = ceil(count($rawResult)/$num_of_rec);

        $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$num_of_rec");
        $stmt->execute();
        $result = $stmt->fetchAll();
    
    




?>

    <section class="section">
        <table class="table">
           
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                if($result){ 
                    $i = 1;
                    foreach($result as $value) { 
                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $value['customer_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetch();
                ?>
                <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <td><?php echo escape($userResult['name']) ?></td>
                    <td><?php echo escape($value['total_amount']) ?></td>
                    <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                    <td>
                        <a href="order_detail.php?id=<?php echo $value['id'] ?>" class="btn btn-primary">View</a>
                        
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


                        