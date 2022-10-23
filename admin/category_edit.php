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
    if(empty($_POST['name'])){
        $nameError = "The name field is required";
    }

    if(empty($_POST['description'])){
        $descError = "The description field is required";
    }

   

    if(!empty($_POST['name']) && !empty($_POST['description'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $updated_at = date('Y-m-d H:i:s',time());
        
            $stmt = $pdo->prepare("UPDATE categories SET name=:name,description=:description,updated_at=:updated WHERE id=:id");
            $result = $stmt->execute([
                ':id' => $id,
                 ':name' => $name,
                 ':description' => $description,
                 ':updated' => $updated_at
     
             ]);
             if($result){
                 echo "<script>alert('Successfully Updated');window.location.href='category_list.php'</script>";
             }
        
    }
   


}

if(isset($_GET['id'])){
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=" . $_GET['id']);
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

                    <button type="submit" name="submit" class="btn btn-success">Update</button>
                    <a href="category_list.php" class="btn btn-warning">Back</a>
                </form>
            </div>


        </section>

    </main><!-- End #main -->

<?php include "footer.php" ?>