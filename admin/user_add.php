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
    }else{
      $nameCheck = preg_match('/^[a-z\d]{4,12}$/i',$_POST['name']);
      if($nameCheck != 1){
        $nameError = "Username must be alphanumeric and contain 4-12 characters";
      }
    }

    if(empty($_POST['email'])){
        $emailError = "The email field is required";
    }else{
        $emailCheck = preg_match('/^[a-z\d]+@[a-z]+\.[a-z]{2,3}(\.[a-z]{2})?$/',$_POST['email']);
        if($emailCheck != 1){
            $emailError = "Email must be a valid address.e.g. me@mydomain.com";
        }

       
    }

    if(empty($_POST['phone'])){
        $phoneError = "The phone field is required";
    }else{
        $phoneCheck = preg_match('/^09[\d]{9}$/',$_POST['phone']);
        if($phoneCheck != 1){
            $phoneError = "Phone number must be a valid Myanmar telephone number (11 digits).e.g.09123456789";
        }

    }

    if(empty($_POST['address'])){
        $addError = "The address field is required";
    }

    if(empty($_POST['password'])){
        $passError = "The password field is required";
    }else{
        $passCheck = preg_match('/^[\w@-]{8,20}$/',$_POST['password']);
        if($passCheck != 1){
            $passError = "Password must alphanumeric (@,_ and - are also allowed) and be 8-20 characters ";
        }
      
    }
  

    if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['address']) && !empty($_POST['password']) && $nameCheck == 1 && $emailCheck == 1 && $passCheck == 1 && $phoneCheck == 1){
        
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    if(empty($_POST['role'])){
        $role = 0;
    }else{
        $role = 1;
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email duplicated')</script>";
    }else{
        if($_FILES['image']['name'] != null){
            $fileName = $_FILES['image']['name'];
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));
            $allowed = ['jpg','jpeg','png'];
            if(in_array($fileActualExt,$allowed)){
                $image =    uniqid('',true) . "." . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
                $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,address,password,role,image) VALUES (:name,:email,:phone,:address,:password,:role,:image)");
               $result = $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':password' => $password,
                    ':role' => $role,
                    ':image' => $image,
        
                ]);
                if($result){
                    echo "<script>alert('Successfully Added');window.location.href='user_list.php'</script>";
                }
                
            }
        }else{

            $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,address,password,role) VALUES (:name,:email,:phone,:address,:password,:role)");
            $result = $stmt->execute([
                 ':name' => $name,
                 ':email' => $email,
                 ':phone' => $phone,
                 ':address' => $address,
                 ':password' => $password,
                 ':role' => $role,
              
             ]);
             if($result){
                 echo "<script>alert('Successfully Added');window.location.href='user_list.php'</script>";
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
            <h3>Users Add</h3>
                <form action="user_add.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control <?php if(!empty($nameError)){ ?>is-invalid<?php } ?>" id="name" >
                        <span style="color:red"><?php echo empty($nameError) ?  "" : $nameError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?php if(!empty($emailError)){ ?>is-invalid<?php } ?>" id="email" >
                        <span style="color:red"><?php echo empty($emailError) ?  "" : $emailError  ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="number" name="phone" class="form-control <?php if(!empty($phoneError)){ ?>is-invalid<?php } ?>" id="phone">
                        <span style="color:red"><?php echo empty($phoneError) ?  "" : $phoneError ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text"  name="address" class="form-control <?php if(!empty($addError)){ ?>is-invalid<?php } ?>" id="address">
                        <span style="color:red"><?php echo empty($addError) ?  "" : $addError ?></span>
                    </div>
                     <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"  name="password" class="form-control <?php if(!empty($passError)){ ?>is-invalid<?php } ?>" id="password">
                        <span style="color:red"><?php echo empty($passError) ?  "" : $passError ?></span>
                    </div>
                    <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="checkbox"  name="role">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                    <a href="user_list.php" class="btn btn-warning">Back</a>
                </form>
            </div>


        </section>

    </main><!-- End #main -->

<?php include "footer.php" ?>