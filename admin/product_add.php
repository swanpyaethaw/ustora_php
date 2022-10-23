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

if(isset($_POST['submit'])){
   

    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) && empty($_POST['price']) ||  empty($_POST['quantity']) || empty($_FILES['image']['name']) ||  is_numeric($_POST['price'])!=1 || is_numeric($_POST['quantity'])!=1){
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
    
        if(empty($_FILES['image']['name'])){
            $imgError = "The image field is required";
        }
   
    }elseif(!empty($_POST['discount']) && is_numeric($_POST['discount'])!=1 ){
        $disError = "Discount must be integer value";
    }else{
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];
        $quantity = $_POST['quantity'];
        $fileName = $_FILES['image']['name'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = ['jpg','jpeg','png'];
        if(in_array($fileActualExt,$allowed)){
            $image =    uniqid('',true) . "." . $fileName;
            move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
            if($discount != null){
                $stmt = $pdo->prepare("INSERT INTO products (name,description,category_id,price,discount,quantity,image) VALUES (:name,:description,:category,:price,:discount,:quantity,:image)");
                $result = $stmt->execute([
                     ':name' => $name,
                     ':description' => $description,
                     ':category' => $category,
                     ':price' => $price,
                     ':discount' => $discount,
                     ':quantity' => $quantity,
                     ':image' => $image,
         
                 ]);
                 if($result){
                     echo "<script>alert('Successfully Added');window.location.href='index.php'</script>";
                 }
            }else{
                $stmt = $pdo->prepare("INSERT INTO products (name,description,category_id,price,quantity,image) VALUES (:name,:description,:category,:price,:quantity,:image)");
                $result = $stmt->execute([
                     ':name' => $name,
                     ':description' => $description,
                     ':category' => $category,
                     ':price' => $price,
                     ':quantity' => $quantity,
                     ':image' => $image,
         
                 ]);
                 if($result){
                     echo "<script>alert('Successfully Added');window.location.href='index.php'</script>";
                 }
            }
         
            
        }
    }

   
    


}


?>
<?php include "header.php" ?>
    <main id="main" class="main">

        <section class="section">
            <div class="container">
            <h3>Products Add</h3>
                <form action="product_add.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control <?php if(!empty($nameError)){ ?>is-invalid<?php } ?>" id="name" >
                        <span style="color:red"><?php echo empty($nameError) ?  "" : $nameError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control <?php if(!empty($descError)){ ?>is-invalid<?php } ?>" id="description" cols="30" rows="10"></textarea>
                        <span style="color:red"><?php echo empty($descError) ?  "" : $descError ?></span>
                    </div>
                    <div class="mb-3">
                        <?php 
                        
                        $catStmt = $pdo->prepare("SELECT * FROM categories");
                        $catStmt->execute();
                        $catResult = $catStmt->fetchAll();
                        
                        ?>
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control <?php if(!empty($catError)){ ?>is-invalid<?php } ?>">
                            <option value="">Select Category</option>
                            <?php 
                          
                            foreach($catResult as $value){
                                ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                            <?php } ?>
                        </select>
                        <span style="color:red"><?php echo empty($catError) ?  "" : $catError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" class="form-control <?php if(!empty($priceError)){ ?>is-invalid<?php } ?>" id="price">
                        <span style="color:red"><?php echo empty($priceError) ?  "" : $priceError ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount</label>
                        <input type="number" name="discount" class="form-control  <?php if(!empty($disError)){ ?>is-invalid<?php } ?>" id="discount">
                        <span style="color:red"><?php echo empty($disError) ?  "" : $disError ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number"  name="quantity" class="form-control <?php if(!empty($qtyError)){ ?>is-invalid<?php } ?>" id="quantity">
                        <span style="color:red"><?php echo empty($qtyError) ?  "" : $qtyError ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control <?php if(!empty($imgError)){ ?>is-invalid<?php } ?>" id="image">
                        <span style="color:red"><?php echo empty($imgError) ?  "" : $imgError ?></span>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                    <a href="index.php" class="btn btn-warning">Back</a>
                </form>
            </div>


        </section>

    </main><!-- End #main -->

<?php include "footer.php" ?>