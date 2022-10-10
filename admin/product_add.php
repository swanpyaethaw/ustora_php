<?php 
session_start();
require "config/config.php";
include "config/common.php";

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location:login.php');
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $fileName = $_FILES['image']['name'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = ['jpg','jpeg','png'];
    if(in_array($fileActualExt,$allowed)){
        $image =    uniqid('',true) . "." . $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
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
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="10"></textarea>
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
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" id="price">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number"  name="quantity" class="form-control" id="quantity">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                    <a href="index.php" class="btn btn-warning">Back</a>
                </form>
            </div>


        </section>

    </main><!-- End #main -->

<?php include "footer.php" ?>