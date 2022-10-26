<?php 
session_start();

require "config/config.php";
require "config/common.php";

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location:login.php');
}

?>

<?php include "header.php" ?>
    
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shopping Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Page title area -->
    
    
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
              
                
                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="woocommerce">
                        <?php if(isset($_SESSION['cart'])) : ?>
                            
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                        <tr>
                                            <th class="product-remove">&nbsp;</th>
                                            <th class="product-thumbnail">&nbsp;</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-quantity">Update Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php 
                                    $total = 0;
                                    foreach($_SESSION['cart'] as $key=>$qty){
                                    $id = str_replace("id","",$key);
                                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
                                    $stmt->execute();
                                    $result = $stmt->fetch();
                                    $total += $result['price'] * $qty;
                                

                                ?>
                                        <tr class="cart_item">
                                            <td class="product-remove">
                                                <a title="Remove this item" class="remove" href="clear.php?id=<?php echo $result['id'] ?>">×</a> 
                                            </td>

                                            <td class="product-thumbnail">
                                                <a href="single-product.html"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="admin/images/<?php echo $result['image'] ?>"></a>
                                            </td>

                                            <td class="product-name">
                                                <a href="product_detail.php?id=<?php echo $result['id'] ?>"><?php echo $result['name'] ?></a> 
                                            </td>

                                            <td class="product-price">
                                                <span class="amount">$<?php echo $result['price'] ?></span> 
                                            </td>

                                            <td class="product-quantity">
                                                <span class="amount"><?php echo $qty ?></span> 
                                            </td>
                                            
                                           
                                            <td class="product-quantity">
                                            
                                                <div class="quantity buttons_added">
                                                <form action="add_to_cart.php" method="POST">
                                                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                                                    <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                                                    
                                                    <input type="number" name="quantity" size="4" class="input-text qty text" title="Qty" value="<?php echo $qty ?>" min="1" step="1">
                                                    <input type="submit" name="update_submit" value="Update Cart"  class="button">
                                                    </form>
                                                   
                                                    
                                                </div>
                                                 
                                            </td>
                                            
                                            

                                            <td class="product-subtotal">
                                                <span class="amount">$<?php echo $result['price'] * $qty ?></span> 
                                            </td>
                                        </tr>
                                        
                                        <?php } ?>
                                        
                                    </tbody>
                                    
                                </table>
                                
                                <a href="sale_order.php" class="btn btn-primary">ORDER</a>
                                <a href="clear_all.php" class="btn btn-primary">CLEAR ALL</a>
                               <?php endif ?>
                          

                            <div class="cart-collaterals">


                            

                            <div class="cart_totals ">
                                <h2>Cart Totals</h2>

                                <table cellspacing="0">
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Cart Subtotal</th>
                                            <td><span class="amount">$<?php echo $total ?></span></td>
                                        </tr>

                                        <tr class="shipping">
                                            <th>Shipping and Handling</th>
                                            <td>Free Shipping</td>
                                        </tr>

                                        <tr class="order-total">
                                            <th>Order Total</th>
                                            <td><strong><span class="amount">$<?php echo $total ?></span></strong> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                           


                            </div>
                        </div>                        
                    </div>                    
                </div>
            </div>
        </div>
    </div>


<?php include "footer.php" ?>
   