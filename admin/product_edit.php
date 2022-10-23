<?php 
session_start();

require "config/config.php";
include "config/common.php";

date_default_timezone_set('Asia/Rangoon');

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location:login.php');
}

if($_SESSION['user_role'] != 1){
    header('location:login.php');
}

if(isset($_POST['submit'])){
    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) && empty($_POST['price']) ||  empty($_POST['quantity']) ||   is_numeric($_POST['price'])!=1 || is_numeric($_POST['quantity'])!=1){
        if(empty($_POST['name'])){
            $nameError = "The name field is required";
        }
    
        if(empty($_POST['description'])){
            $descError = "The description field is required";
        }
    
        if(empty($_POST['category'])){
            $catError = "The category field is required";
        }
      
    
        if(empty($_POST['price'])){
            $priceError = "The price field is required";
        }elseif(is_numeric($_POST['price'])!=1){
            $priceError = "Price must be integer value";
        }
    
        if(empty($_POST['quantity'])){
            $qtyError = "The quantity field is required";
        }elseif(is_numeric($_POST['quantity'])!=1){
            $qtyError = "Quantity must be integer value";
        }
    
      
   
    }elseif(!empty($_POST['discount']) && is_numeric($_POST['discount'])!=1 ){
        $disError = "Discount must be integer value";
    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];
        $quantity = $_POST['quantity'];
       
            if($discount != null){
                if($_FILES['image']['name'] != null){
                    $fileName = $_FILES['image']['name'];
                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));
                    $allowed = ['jpg','jpeg','png'];
                    if(in_array($fileActualExt,$allowed)){
                        $image =    uniqid('',true) . "." . $fileName;
                        move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
                        $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,discount=:discount,quantity=:quantity,image=:image WHERE id=:id");
                        $result = $stmt->execute([
                             ':id' => $id,
                             ':name' => $name,
                             ':description' => $description,
                             ':category' => $category,
                             ':price' => $price,
                             ':discount' => $discount,
                             ':quantity' => $quantity,
                             ':image' => $image
                 
                         ]);
                         if($result){
                             echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
                         }
                        }
                }else{
                    $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,discount=:discount,quantity=:quantity WHERE id=:id");
                    $result = $stmt->execute([
                         ':id' => $id,
                         ':name' => $name,
                         ':description' => $description,
                         ':category' => $category,
                         ':price' => $price,
                         ':discount' => $discount,
                         ':quantity' => $quantity
                     ]);
                     if($result){
                         echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
                     }
                
        }
    }else{
        if($_FILES['image']['name'] != null){
            $fileName = $_FILES['image']['name'];
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));
            $allowed = ['jpg','jpeg','png'];
            if(in_array($fileActualExt,$allowed)){
                $image =    uniqid('',true) . "." . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
                $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity,image=:image WHERE id=:id");
                $result = $stmt->execute([
                     ':id' => $id,
                     ':name' => $name,
                     ':description' => $description,
                     ':category' => $category,
                     ':price' => $price,
                     ':quantity' => $quantity,
                     ':image' => $image
         
                 ]);
                 if($result){
                     echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
                 }
                }
        }else{
            $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity WHERE id=:id");
            $result = $stmt->execute([
                 ':id' => $id,
                 ':name' => $name,
                 ':description' => $description,
                 ':category' => $category,
                 ':price' => $price,
                 ':quantity' => $quantity
             ]);
             if($result){
                 echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
             }
        
    }
    }

}
}

if(isset($_GET['id'])){
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
                        <input type="text" name="name" class="form-control <?php if(!empty($nameError)){ ?>is-invalid<?php } ?>" id="name" value="<?php echo $result['name'] ?>">
                        <span style="color:red"><?php echo empty($nameError) ?  "" : $nameError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control <?php if(!empty($descError)){ ?>is-invalid<?php } ?>" id="description" cols="30" rows="10"><?php echo $result['description'] ?></textarea>
                        <span style="color:red"><?php echo empty($descError) ?  "" : $descError  ?></span>
                    </div>

                    <div class="mb-3">
                        <?php 
                            $catStmt = $pdo->prepare("SELECT * FROM categories");
                            $catStmt->execute();
                            $catResult = $catStmt->fetchAll();
                        ?>
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control <?php if(!empty($catError)){ ?>is-invalid<?php } ?>">
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
                        <span style="color:red"><?php echo empty($catError) ?  "" : $catError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" class="form-control <?php if(!empty($priceError)){ ?>is-invalid<?php } ?>" id="price" value="<?php echo $result['price'] ?>">
                        <span style="color:red"><?php echo empty($priceError) ?  "" : $priceError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount</label>
                        <input type="number" name="discount" class="form-control  <?php if(!empty($disError)){ ?>is-invalid<?php } ?>" id="discount" value="<?php echo $result['discount'] ?>">
                        <span style="color:red"><?php echo empty($disError) ?  "" : $disError ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number"  name="quantity" class="form-control <?php if(!empty($qtyError)){ ?>is-invalid<?php } ?>" id="quantity" value="<?php echo $result['quantity'] ?>">
                        <span style="color:red"><?php echo empty($qtyError) ?  "" : $qtyError  ?></span>
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