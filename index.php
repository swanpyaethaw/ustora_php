<?php 
session_start();
require "config/config.php";
require "config/common.php";



?>




<?php include "header.php" ?>

    
<?php 
      
            $stmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 AND price >= 500");
            $stmt->execute();
            $result = $stmt->fetchAll(); 


?>
    <div class="slider-area">
        	<!-- Slider -->
			<div class="block-slider block-slider4">
				<ul class="" id="bxslider-home4">
                    <?php foreach($result as $value) : ?>
					<li>
						<img src="admin/images/<?php echo $value['image'] ?>" alt="Slide" style="width: 500px;height:500px" class="img-fluid">
						<div class="caption-group">
							<h2 class="caption title">
								<?php echo $value['name'] ?>
							</h2>
							<a class="caption button-radius" href="shop.php"><span class="icon"></span>Shop now</a>
						</div>
					</li>
                    <?php endforeach ?>
				
				</ul>
			</div>
			<!-- ./Slider -->
    </div> <!-- End slider area -->
    
   <?php 
            $currentDate = date('Y-m-d');
            $fromDate = date('Y-m-d',strtotime('+1 day' . $currentDate));
            $toDate = date('Y-m-d',strtotime('-8 day' . $currentDate));
           
                $lateStmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 AND updated_at < :from AND updated_at >= :to");
                $lateStmt->execute([
                    ':from' => $fromDate,
                    ':to' => $toDate 
                ]);
                $lateResult = $lateStmt->fetchAll();

   
   ?>
    
    <div class="maincontent-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="latest-product">
                        <h2 class="section-title">Latest Products</h2>
                        <div class="product-carousel">
                            <?php foreach($lateResult as $value) : ?>
                            <div class="single-product">
                                <div class="product-f-image">
                                    <img src="admin/images/<?php echo $value['image'] ?>" alt="">
                                    <div class="product-hover">
                                        <a href="add_to_cart.php?cid=<?php echo $value['id'] ?>" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                        <a href="product_detail.php?id=<?php echo $value['id'] ?>" class="view-details-link"><i class="fa fa-link"></i> See details</a>
                                    </div>
                                </div>
                                
                                <h2><a href="product_detail.php?id=<?php echo $value['id'] ?>"><?php echo $value['name'] ?></a></h2>
                                
                                <div class="product-carousel-price">
                                    <ins>$<?php echo $value['price'] ?></ins> <?php if($value['discount'] != null) : ?><del>$<?php echo $value['discount'] ?></del><?php endif ?>
                                </div> 
                            </div>
                            <?php endforeach ?>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End main content area -->
    
    <div class="brands-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="brand-wrapper">
                        <div class="brand-list">
                            <img src="img/brand1.png" alt="">
                            <img src="img/brand2.png" alt="">
                            <img src="img/brand3.png" alt="">
                            <img src="img/brand4.png" alt="">
                            <img src="img/brand5.png" alt="">
                            <img src="img/brand6.png" alt="">
                            <img src="img/brand1.png" alt="">
                            <img src="img/brand2.png" alt="">                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End brands area -->
    <?php 

        $sellStmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id having SUM(quantity) > 3  ORDER BY id DESC LIMIT 0,2");
        $sellStmt->execute();
        $sellResult = $sellStmt->fetchAll();
        

    ?>
    <div class="product-widget-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="single-product-widget">
                        <h2 class="product-wid-title">Top Sellers</h2>
                        <a href="top_seller_view.php" class="wid-view-more">View All</a>
                        <?php foreach($sellResult as $value){
                            $pStmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $value['product_id']);
                            $pStmt->execute();
                            $pResult = $pStmt->fetch();

                            $rvStmt1 = $pdo->prepare("SELECT COUNT(*) as total, SUM(rating) as rating FROM product_review WHERE product_id=" . $value['product_id']);
                            $rvStmt1->execute();
                            $rvResult1 = $rvStmt1->fetch(\PDO::FETCH_ASSOC);
                            $pdrating = 0;
                            if ($rvResult1['total'] > 0) {
                                $pdrating = round(($rvResult1['rating']/($rvResult1['total'] * 5)) * 100);
                            }
                            $mypercent = 0;

                            if ($pdrating > 80 && $pdrating <= 100){
                                $mypercent = 5;
                            } else if ($pdrating > 60 && $pdrating <= 80){
                                $mypercent = 4;
                            } else if ($pdrating > 40 && $pdrating <= 60){
                                $mypercent = 3;
                            } else if ($pdrating > 20 && $pdrating <= 40){
                                $mypercent = 2;
                            } else if ($pdrating > 0 && $pdrating <= 20){
                                $mypercent = 1;
                            } else {
                                $mypercent = 0;
                            }
                        ?>
                            
                        <div class="single-wid-product">
                            <a href="single-product.html"><img src="admin/images/<?php echo $pResult['image'] ?>" alt="" class="product-thumb"></a>
                            <h2><a href="single-product.html"><?php echo $pResult['name'] ?></a></h2>
                            <?php if ($mypercent > 0) { ?>
                                <div class="product-wid-rating">
                                    <?php for($i = 0; $i < 5; $i++) { ?>
                                        <?php if($i < $mypercent) { ?>
                                            <i class="fa fa-star" style="color: gold"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-star" style="color: black"></i>
                                        <?php } ?> 
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="product-wid-price">
                                <ins>$<?php echo $pResult['price'] ?></ins> <?php if($pResult['discount'] != null) : ?><del>$<?php echo $pResult['discount'] ?></del><?php endif ?>
                            </div>                            
                        </div>
                        <?php } ?>
                      
                    </div>
                </div>

                <!-- Recently View -->
                <div class="col-md-4">
                    <div class="single-product-widget">
                        <h2 class="product-wid-title">Recently Viewed</h2>
                        <a href="#" class="wid-view-more">View All</a>
                        <div class="single-wid-product">
                            <a href="single-product.html"><img src="img/product-thumb-4.jpg" alt="" class="product-thumb"></a>
                            <h2><a href="single-product.html">Sony playstation microsoft</a></h2>
                            <div class="product-wid-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="product-wid-price">
                                <ins>$400.00</ins> <del>$425.00</del>
                            </div>                            
                        </div>
                        <div class="single-wid-product">
                            <a href="single-product.html"><img src="img/product-thumb-1.jpg" alt="" class="product-thumb"></a>
                            <h2><a href="single-product.html">Sony Smart Air Condtion</a></h2>
                            <div class="product-wid-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="product-wid-price">
                                <ins>$400.00</ins> <del>$425.00</del>
                            </div>                            
                        </div>
                        <div class="single-wid-product">
                            <a href="single-product.html"><img src="img/product-thumb-2.jpg" alt="" class="product-thumb"></a>
                            <h2><a href="single-product.html">Samsung gallaxy note 4</a></h2>
                            <div class="product-wid-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="product-wid-price">
                                <ins>$400.00</ins> <del>$425.00</del>
                            </div>                            
                        </div>
                    </div>
                </div>

                <?php 
                
                $currentDate = date('Y-m-d');
                $fromDate = date('Y-m-d',strtotime('+1 day' . $currentDate));
                $toDate = date('Y-m-d',strtotime('-8 day' . $currentDate));
                    $newStmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 AND price >= 500 AND updated_at < :from AND updated_at >= :to ORDER BY id DESC  LIMIT 0,3");
                    $newStmt->execute([
                        ':from' => $fromDate,
                        ':to' => $toDate
                    ]);
                    $newResult = $newStmt->fetchAll();

                ?>
                <div class="col-md-4">
                    <div class="single-product-widget">
                        <h2 class="product-wid-title">Top New</h2>
                        <a href="#" class="wid-view-more">View All</a>
                        <?php 
                                foreach($newResult as $value){
                        ?>
                        <div class="single-wid-product">
                            <a href="product_detail.php?id=<?php echo $value['id'] ?>"><img src="admin/images/<?php echo $value['image'] ?>" alt="" class="product-thumb"></a>
                            <h2><a href="product_detail.php?id=<?php echo $value['id'] ?>"><?php echo $value['name'] ?></a></h2>
                            <div class="product-wid-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="product-wid-price">
                                <ins><?php echo $value['price'] ?></ins> <?php if($value['discount'] != null) : ?><del>$<?php echo $value['discount'] ?></del><?php endif ?>
                            </div>                            
                        </div>
                        <?php } ?>
                       
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End product widget area -->
    
    <?php include "footer.php" ?>