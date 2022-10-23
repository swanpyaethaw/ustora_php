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
    if(empty($_POST['name'])){
        $nameError = "The name field is required";
    }

    if(empty($_POST['description'])){
        $descError = "The description field is required";
    }


    if(!empty($_POST['name']) && !empty($_POST['description'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
        $stmt = $pdo->prepare("INSERT INTO categories (name,description) VALUES (:name,:description)");
        $result = $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
        if($result){
            echo "<script>alert('Successfully Added');window.location.href='category_list.php'</script>";
        }
        
   
    }

}


?>
<?php include "header.php" ?>
    <main id="main" class="main">

        <section class="section">
            <div class="container">
            <h3>Categories Add</h3>
                <form action="category_add.php" method="POST" enctype="multipart/form-data">
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
                   
                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                    <a href="category_list.php" class="btn btn-warning">Back</a>
                </form>
            </div>


        </section>

    </main><!-- End #main -->

<?php include "footer.php" ?>