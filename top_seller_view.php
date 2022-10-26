<?php 

session_start();


require "config/config.php";
require "config/common.php";

    if(isset($_POST['search'])){
        setcookie('search',$_POST['search'],time()+3600,'/');
    }else{
        if(empty($_GET['pageno'])){
            setcookie('search','',time()-3600,'/');
        }
    }

    if(empty($_GET['pageno'])){
        $pageno = 1;
    }else{
        $pageno = $_GET['pageno'];
    }

    $num_of_rec = 3;
    $offset = ($pageno-1) * $num_of_rec;


    if(empty($_POST['search']) && empty($_COOKIE['search'])){

        $rawStmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id having SUM(quantity)>=3");
        $rawStmt->execute();
        $rawResult = $rawStmt->fetchAll();

       
        $total_pages = ceil(count($rawResult)/$num_of_rec);

        $stmt = $pdo->prepare("SELECT * FROM sale_order_detail JOIN products ON sale_order_detail.product_id = products.id  GROUP BY sale_order_detail.product_id having SUM(sale_order_detail.quantity)>=3 ORDER BY sale_order_detail.id DESC LIMIT $offset,$num_of_rec");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       


      
    }else{
        $search = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
        $rawStmt = $pdo->prepare("SELECT * FROM sale_order_detail JOIN products ON sale_order_detail.product_id = products.id  WHERE products.name LIKE '%$search%' GROUP BY sale_order_detail.product_id having SUM(sale_order_detail.quantity)>=3");
        $rawStmt->execute();
        $rawResult = $rawStmt->fetchAll();
    
        $total_pages = ceil(count($rawResult)/$num_of_rec);
    
        $stmt = $pdo->prepare("SELECT * FROM sale_order_detail JOIN products ON sale_order_detail.product_id = products.id  WHERE products.name LIKE '%$search%' GROUP BY product_id having SUM(quantity)>=3 ORDER BY sale_order_detail.id DESC LIMIT $offset,$num_of_rec");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  

?>

<?php include "header.php" ?>
    
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shop</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <?php 
                
                foreach($result as $key=>$value) {
                  
              
                    
                    ?>
                <div class="col-md-3 col-sm-6">
                    <div class="single-shop-product">
                        <div class="product-upper">
                            <img src="admin/images/<?php echo escape($value['image']) ?>" alt="">
                        </div>
                        <h2><a href="product_detail.php?id=<?php echo $value['product_id'] ?>"><?php echo  escape($value['name']) ?></a></h2>
                        <div class="product-carousel-price">
                            <ins>$<?php echo escape($value['price']) ?></ins> <?php if(escape($value['discount']) != null) : ?><del>$<?php echo escape($value['discount']) ?></del><?php endif ?>
                        </div>  
                        
                        <div class="product-option-shop">
                            <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="add_to_cart.php?cid=<?php echo $value['product_id'] ?>">Add to cart</a>
                        </div>                       
                    </div>
                </div>
                <?php   } ?>
            
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="product-pagination text-center">
                        <nav>
                          <ul class="pagination">
                            <li class="<?php if($pageno <= 1) : ?>disabled<?php endif  ?>">
                              <a href="?pageno=<?php if($pageno <= 1) { ?><?php echo "#" ?><?php }else{  ?><?php echo $pageno-1 ?><?php } ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>
                            <?php 
                            for($i = 1;$i <= $total_pages;$i++){
                            ?>
                            <li><a href="?pageno=<?php echo $i ?>"><?php echo $i ?></a></li>
                            <?php } ?>
                            
                            <li  class="<?php if($pageno >= $total_pages) : ?>disabled<?php endif  ?>">
                              <a href="?pageno=<?php if($pageno >= $total_pages) { ?><?php echo "#" ?><?php }else{  ?><?php echo $pageno+1 ?><?php } ?>" aria-label="Next">
                                <span aria-hidden="true" >&raquo;</span>
                              </a>
                            </li>
                          </ul>
                        </nav>                        
                    </div>
                </div>
            </div>
        </div>
    </div>


  <?php include "footer.php" ?>