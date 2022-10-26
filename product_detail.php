<?php 

session_start();
require "config/config.php";
require "config/common.php";

if(empty($_SESSION['view'][$_GET['id']])){
    $_SESSION['view'][$_GET['id']] = $_GET['id'];
}

echo "<pre>";
print_r($_SESSION['view']);

print_r(array_slice($_SESSION['view'],-3,3));


















if(isset($_POST['submit_review'])){
    if(empty($_SESSION['user_id'])){
        echo "<script>alert('Please Log in to submit review');window.location.href='login.php'</script>";
    }else{
        $review = $_POST['review'];
        $pid = $_POST['product_id'];
        $uid = $_SESSION['user_id'];
       

        $rvsStmt = $pdo->prepare("INSERT INTO product_review (user_id,product_id,review) VALUES (:uid,:pid,:review)");
        $rvsStmt->execute([
            ':uid' => $uid,
            ':pid' => $pid,
            ':review' => $review
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

                                <div role="tabpanel">
                                    <ul class="product-tab" role="tablist">
                                        <li role="presentation" class="active"><a href="#home" aria-controls="home"
                                                role="tab" data-toggle="tab">Description</a></li>
                                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                                                data-toggle="tab">Reviews</a></li>
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
                                                        <div class="rating-wrap-post">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                </div>
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

                            <div style="display:flex;align-items:baseline;gap:1em;margin-bottom:1em;">
                                <div style="display:flex;">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <span><?php echo $value['updated_at'] ?></span>
                            </div>
                            <!-- col-3 -->
                            <p style="margin-bottom: 1em;"><?php echo $value['review'] ?></p>
                            <?php if($_SESSION['user_id'] === $userResult['id']) : ?>
                            <div style="display:flex;justify-content:end; gap:1em;">
                                <a href="review_edit.php?id=<?php echo $value['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <a href="review_delete.php?id=<?php echo $value['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                            </div>
                            <?php endif ?>
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