<?php 
session_start();
require "config/config.php";
require "config/common.php";

if(isset($_POST['submit'])){

    if(empty($_POST['review']) || empty($_POST['rating'])){
        if(empty($_POST['review'])){
            $reviewError = "Review field is required";
        }

        if(empty($_POST['rating'])){
            $ratingError = "Rating is required";
        }
    }else{
        $id = $_POST['review_id'];
        $review = $_POST['review'];
        $pid = $_POST['product_id'];
        $rating = $_POST['rating'];
     
    
        $stmt = $pdo->prepare("UPDATE product_review SET review=:review,rating=:rating WHERE id=:id");
        $result = $stmt->execute([
            ':review' => $review,
            ':rating' => $rating,
            ':id' => $id
        ]);
        if($result){
            header("location:product_detail.php?id=$pid");
        }
    }
  


}

$stmt = $pdo->prepare("SELECT * FROM product_review WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetch();



?>

<?php include "header.php" ?>

<div style="display:flex;justify-content:center;">
    <form action="" method="POST">
        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">

        <span style="color:red"><?php echo empty($reviewError) ? "" : $reviewError ?></span>
        <input type="hidden" name="review_id" value="<?php echo $result['id'] ?>">

        <input type="hidden" name="product_id" value="<?php echo $result['product_id'] ?>">

        <input type="hidden" name="rating" id="rating" value="">
        <span style="color:red"><?php echo empty($ratingError) ? "" : $ratingError ?></span>
                <div class="rating-wrap-post">

                    <?php for($i = 1; $i <= 5; $i++) { ?>
                    <?php if($i<=$result['rating']){  ?>
                        <i class="fa fa-star rating" data-value="<?php echo $i ?>" style="color: gold"></i>
                    <?php }else{ ?>
                        <i class="fa fa-star rating" data-value="<?php echo $i ?>"></i>
                    <?php } ?>
                   
                    <?php } ?>
                </div>

        <p><label for="review">Your review</label> <textarea name="review" class="form-control" style="width:320px;resize:none;" rows="5"><?php echo $result['review'] ?></textarea></p>
        <p><input type="submit" name="submit" value="Edit"></p>
    </form>
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