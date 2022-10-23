<?php 
session_start();
require "config/config.php";
require "config/common.php";

if(isset($_POST['submit'])){
    $id = $_POST['review_id'];
    $review = $_POST['review'];
    $pid = $_POST['product_id'];
 

    $stmt = $pdo->prepare("UPDATE product_review SET review=:review WHERE id=:id");
    $result = $stmt->execute([
        ':review' => $review,
        ':id' => $id
    ]);
    if($result){
        header("location:product_detail.php?id=$pid");
    }

  


}

$stmt = $pdo->prepare("SELECT * FROM product_review WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetch();

$pStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$result['product_id']);
$pStmt->execute();
$pResult = $pStmt->fetch();


?>

<?php include "header.php" ?>

<div style="display:flex;justify-content:center;">
    <form action="" method="POST">
        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
        <input type="hidden" name="review_id" value="<?php echo $result['id'] ?>">
        <input type="hidden" name="product_id" value="<?php echo $pResult['id'] ?>">

        <div class="rating-wrap-post">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>

        <p><label for="review">Your review</label> <textarea name="review" class="form-control" style="width:320px;resize:none;" rows="5"><?php echo $result['review'] ?></textarea></p>
        <p><input type="submit" name="submit" value="Edit"></p>
    </form>
</div>

<?php include "footer.php" ?>