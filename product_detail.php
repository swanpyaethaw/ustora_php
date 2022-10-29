<?php 

session_start();
require "config/config.php";
require "config/common.php";

// if(empty($_SESSION['view'][$_GET['id']])){
//     $_SESSION['view'][$_GET['id']] = $_GET['id'];
// }

if(isset($_SESSION['user_id'])){
    if(empty($_SESSION['view'][$_SESSION['user_id']])){
        $_SESSION['view'][$_SESSION['user_id']] = [];
        $_SESSION['view'][$_SESSION['user_id']][] = $_GET['id'];
    }else{
        $_SESSION['view'][$_SESSION['user_id']][] = $_GET['id'];
    }
    echo "<pre>";
    print_r($_SESSION['view']);
}









 
    





if(isset($_POST['submit_review'])){
    if(empty($_POST['review']) || empty($_POST['rating'])){
        if(empty($_POST['review'])){
            $reviewError = "Review field is required";
        }

        if(empty($_POST['rating'])){
            $ratingError = "Rating is required";
        }
        
    }else{
        $review = $_POST['review'];
        $pid = $_POST['product_id'];
        $rating = $_POST['rating'];
        $uid = $_SESSION['user_id'];
       

        $rvsStmt = $pdo->prepare("INSERT INTO product_review (user_id,product_id,review,rating) VALUES (:uid,:pid,:review,:rating)");
        $rvsStmt->execute([
            ':uid' => $uid,
            ':pid' => $pid,
            ':review' => $review,
            ':rating' => $rating 
        ]);
    }
        
  
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
                
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->fetch();

                  
                
                ?>
            <div class="col-md-12">
                <div class="product-content-right">


                    <div class="row">

                        <div class="col-sm-6">
                            <div class="product-images">
                                <div class="product-main-img">
                                    <img src="admin/images/<?php echo $result['image'] ?>" alt="">
                                </div>


                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="product-inner">
                                <h2 class="product-name"><?php echo $result['name'] ?></h2>
                                <div class="product-inner-price">
                                    <ins>$<?php echo $result['price'] ?></ins>
                                    <?php if($result['discount'] != null) : ?><del>$<?php echo $result['discount'] ?></del><?php endif ?>
                                </div>

                                <form action="add_to_cart.php" class="cart" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                                    <div class="quantity">
                                        <input type="number" size="4" class="input-text qty text" title="Qty"
                                            name="quantity" min="1" step="1">

                                    </div>
                                    <button class="add_to_cart_button" name="submit" type="submit">Add to cart</button>
                                </form>
                                <?php 
                                    
                                    $catStmt = $pdo->prepare("SELECT * FROM categories WHERE id=" . $result['category_id']);
                                    $catStmt->execute();
                                    $catResult = $catStmt->fetch();
                                    
                                    ?>
                                <div class="product-inner-category">
                                    <p>Category: <span><?php echo $catResult['name'] ?></span>.
                                </div>
                                        <?php 
                                        
                                        if(isset($_SESSION['user_id'])){
                                            $reviews = $pdo->prepare("SELECT * FROM product_review WHERE user_id =". $_SESSION['user_id']." AND product_id=" . $_GET['id']);
                                            $reviews->execute();
                                            $reviews = $reviews->fetchAll();
                                            $reviewCount = count($reviews);
                                        }
                                        
                                        
                                        
                                        ?>
                                <div role="tabpanel">
                                    <ul class="product-tab" role="tablist">
                                        <li role="presentation" class="active"><a href="#home" aria-controls="home"
                                                role="tab" data-toggle="tab">Description</a></li>
                                                <?php if(isset($_SESSION['user_id'])) {
                                                    if($reviewCount < 1){
                                                ?>
                                       
                                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                                                data-toggle="tab">Reviews</a></li>
                                        <?php }} ?>
                                       
                                    </ul>
                                    <div class="tab-content">

                                        <div role="tabpanel" class="tab-pane fade in active" id="home">
                                            <h2>Product Description</h2>
                                            <p><?php echo $result['description'] ?></p>
                                        </div>
                                       
                                        <div role="tabpanel" class="tab-pane fade" id="profile">
                                            <h2>Reviews</h2>
                                            <div class="submit-review">

                                                <div class="rating-chooser">
                                                    <p>Your rating</p>
                                                    <form action="" method="POST">
                                                        <input type="hidden" name="_token"
                                                            value="<?php echo $_SESSION['_token'] ?>">
                                                        <input type="hidden" name="product_id"
                                                            value="<?php echo $result['id'] ?>">
                                                        <input type="hidden" name="rating" id="rating" value="">
                                                        <span style="color:red"><?php echo empty($ratingError) ? "" : $ratingError ?></span>
                                                        <div class="rating-wrap-post">
                                                            <i data-value="1" class="fa fa-star rating"></i>
                                                            <i data-value="2" class="fa fa-star rating"></i>
                                                            <i data-value="3" class="fa fa-star rating"></i>
                                                            <i data-value="4" class="fa fa-star rating"></i>
                                                            <i data-value="5" class="fa fa-star rating"></i>
                                                        </div>
                                                </div>
                                                <span style="color:red"><?php echo empty($reviewError) ? "" : $reviewError ?></span>
                                                <p><label for="review">Your review</label> <textarea name="review" id=""
                                                        cols="30" rows="10"></textarea></p>
                                                <p><input type="submit" name="submit_review" value="Submit"></p>
                                                </form>

                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Review Card -->
                    <?php
                        $rvStmt1 = $pdo->prepare("SELECT COUNT(*) as total, SUM(rating) as rating FROM product_review WHERE product_id=" . $_GET['id']);
                        $rvStmt1->execute();
                        $rvStmt1 = $rvStmt1->fetch();
                        $pdrating = 0;
                        if ($rvStmt1['total'] > 0) {
                            $pdrating = round(($rvStmt1['rating']/($rvStmt1['total'] * 5)) * 100);
                        }
                        $star =  5 * ($pdrating/100);
                        $one = $pdo->prepare("SELECT COUNT(rating) as rating_count, rating, product_id FROM product_review WHERE product_id=" . $_GET['id'] . " AND rating = 1 GROUP BY rating");
                        $one->execute();
                        $one = $one->fetch();

                        $two = $pdo->prepare("SELECT COUNT(rating) as rating_count, rating, product_id FROM product_review WHERE product_id=" . $_GET['id'] . " AND rating = 2 GROUP BY rating");
                        $two->execute();
                        $two = $two->fetch();

                        $three = $pdo->prepare("SELECT COUNT(rating) as rating_count, rating, product_id FROM product_review WHERE product_id=" . $_GET['id'] . " AND rating = 3 GROUP BY rating");
                        $three->execute();
                        $three = $three->fetch();

                        $four = $pdo->prepare("SELECT COUNT(rating) as rating_count, rating, product_id FROM product_review WHERE product_id=" . $_GET['id'] . " AND rating = 4 GROUP BY rating");
                        $four->execute();
                        $four = $four->fetch();

                        $five = $pdo->prepare("SELECT COUNT(rating) as rating_count, rating, product_id FROM product_review WHERE product_id=" . $_GET['id'] . " AND rating = 5 GROUP BY rating");
                        $five->execute();
                        $five = $five->fetch();
                    ?>
                    <div class="all">
                        <p class="total">Star Ratio: <?php echo $star ? number_format($star, 1): 0 ?></p>
                        <p class="total">Total Users: <?php echo $rvStmt1['total'] ?></p>
                    </div>
                    <div class="rating">
                        <ul style="list-style-type: none">
                            <?php for ($i=0; $i<5; $i++) { ?>
                            <li>
                                <span><?php echo 5-$i ?></span> => 
                                <?php  
                                    $myarray = [
                                        ['name' => 1, 'value' => $one],
                                        ['name' => 2, 'value' => $two],
                                        ['name' => 3, 'value' => $three],
                                        ['name' => 4, 'value' => $four],
                                        ['name' => 5, 'value' => $five],
                                    ];
                                    $index = array_search(5-$i, array_column($myarray, 'name'));
                                    if ($myarray[$index]['value'] && $myarray[$index]['value']['rating'] == 5-$i && $myarray[$index]['value']['rating_count'] > 0) {
                                        echo $myarray[$index]['value']['rating_count'];
                                    } else {
                                        echo 0;
                                    }
                                ?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php 
                    
                    $rvStmt = $pdo->prepare("SELECT * FROM product_review WHERE product_id=".$_GET['id']);
                    $rvStmt->execute();
                    $rvResult = $rvStmt->fetchAll();
                    
                    ?>
                    <?php foreach($rvResult as $value) {
                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $value['user_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetch();

                        $pStmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $value['product_id']);
                        $pStmt->execute();
                        $pResult = $pStmt->fetch();

                        
                     ?>
                    <div style="display: grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1em;">

                        <article class="card" style="min-width: 18rem;padding:0.5em;">
                            <!-- col-1 -->
                            <div style="display:flex;align-items:baseline;gap:1em;margin-bottom:0.5em;">
                                <div style="width:32px;height:32px;border-radius:50%;overflow:hidden;">
                                    <img src="<?php if($userResult['image'] != null) { ?>admin/images/<?php echo $userResult['image'] ?><?php }else{ ?>admin/dummy/OIP (1).jpg<?php } ?>"
                                        alt="" class="img-fluid">
                                </div>
                                <h2 style="font-size: 2rem;"><?php echo $userResult['name'] ?></h2>
                            </div>
                            <!-- col-2 -->
                            <?php if ($value['rating'] > 0) { ?>
                            <div style="display:flex;align-items:baseline;gap:1em;margin-bottom:1em;">
                                <div style="display:flex;">
                                    <?php for($i = 0; $i < 5; $i++) { ?>
                                    <?php if($i < $value['rating']) { ?>
                                    <i class="fa fa-star" style="color: gold"></i>
                                    <?php } else { ?>
                                    <i class="fa fa-star"></i>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <span><?php echo $value['updated_at'] ?></span>
                            </div>
                            <?php } ?>
                            <!-- col-3 -->
                            <p style="margin-bottom: 1em;"><?php echo $value['review'] ?></p>
                            <?php if(isset($_SESSION['user_id'])){
                                if($_SESSION['user_id'] === $userResult['id']) { ?>
                            <div style="display:flex;justify-content:end; gap:1em;">
                                <a href="review_edit.php?id=<?php echo $value['id'] ?>"
                                    class="btn btn-sm btn-info">Edit</a>
                                <a href="review_delete.php?id=<?php echo $value['id'] ?>"
                                    class="btn btn-sm btn-danger">Delete</a>
                            </div>
                            <?php }} ?>
                        </article>
                    </div>




                    <?php } ?>

                    <?php 
                        
                        $repStmt = $pdo->prepare("SELECT * FROM products WHERE category_id=" . $result['category_id']);
                        $repStmt->execute();
                        $repResult = $repStmt->fetchAll();
                        
                        ?>
                    <div class="related-products-wrapper">
                        <h2 class="related-products-title">Related Products</h2>
                        <div class="related-products-carousel">
                            <?php foreach($repResult as $value) : ?>
                            <div class="single-product">
                                <div class="product-f-image">
                                    <img src="admin/images/<?php echo $value['image'] ?>" alt="">
                                    <div class="product-hover">
                                        <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Add to
                                            cart</a>
                                        <a href="?id=<?php echo $value['id'] ?>" class="view-details-link"><i
                                                class="fa fa-link"></i> See details</a>
                                    </div>
                                </div>

                                <h2><a href=""><?php echo $value['name'] ?></a></h2>

                                <div class="product-carousel-price">
                                    <ins>$<?php echo $value['price'] ?></ins>
                                    <?php if($value['discount'] != null) : ?><del>$<?php echo $value['discount'] ?></del><?php endif ?>
                                </div>
                            </div>
                            <?php endforeach ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<?php include "footer.php" ?>

<script type="text/javascript">
$(function() {
    var rating = 0
    $(document).on('click', '.rating', function() {
        if (rating !== $(this).data('value')) {
            var value = $(this).data('value')
            $('.rating').each(function() {
                if ($(this).data('value') <= value) $(this).css('color', 'gold')
                else $(this).css('color', 'black')
            })
            rating = value
            $('#rating').val(value)
        } else {
            $('.rating').each(function() {
                $(this).css('color', 'black')
            })
            rating = 0
            $('#rating').val(value)
        }
    })
})
</script>