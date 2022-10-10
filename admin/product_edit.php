<?php 
session_start();

require "config/config.php";
include "config/common.php";

date_default_timezone_set('Asia/Rangoon');

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location:login.php');
}

if(isset($_POST['submit'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $updated_at = date('Y-m-d H:i:s',time());
    if($_FILES['image']['name'] != null){
        $fileName = $_FILES['image']['name'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = ['jpg','jpeg','png'];
        if(in_array($fileActualExt,$allowed)){
            $image =    uniqid('',true) . "." . $fileName;
            move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
            $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity,image=:image,updated_at=:updated WHERE id=:id");
           $result = $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':description' => $description,
                ':category' => $category,
                ':price' => $price,
                ':quantity' => $quantity,
                ':image' => $image,
                ':updated' => $updated_at
            ]);
            if($result){
                echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
            }
    }
  
        
    }else{
        $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity,updated_at=:updated WHERE id=:id");
        $result = $stmt->execute([
            ':id' => $id,
             ':name' => $name,
             ':description' => $description,
             ':category' => $category,
             ':price' => $price,
             ':quantity' => $quantity,
             ':updated' => $updated_at
 
         ]);
         if($result){
             echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
         }
    }


}

if(!empty($_GET['id'])){
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch();

}


?>
<?php include "header.php" ?>
    <main id="main" class="main">

        <section class="section">
            <div class="container">
            <h3>Products Edit</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                    <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?php echo $result['name'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10"><?php echo $result['description'] ?></textarea>
                    </div>

                    <div class="mb-3">
                        <?php 
                            $catStmt = $pdo->prepare("SELECT * FROM categories");
                            $catStmt->execute();
                            $catResult = $catStmt->fetchAll();
                        ?>
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="#">Select Category</option>
                            <?php 
                              
                            foreach($catResult as $value){
                                ?>
                              <?php if($value['id'] === $result['category_id']){ ?>
                            <option value="<?php echo $value['id'] ?>" selected><?php echo $value['name'] ?></option>
                              <?php }else{ ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                <?php } ?>
                         
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" id="price" value="<?php echo $result['price'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number"  name="quantity" class="form-control" id="quantity" value="<?php echo $result['quantity'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                        <img src="images/<?php echo $result['image'] ?>" alt="" style="width:100px;height:100px" class="my-3">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Update</button>
                    <a href="index.php" class="btn btn-warning">Back</a>
                </form>
            </div>


        </section>

    </main><!-- End #main -->

<?php include "footer.php" ?>