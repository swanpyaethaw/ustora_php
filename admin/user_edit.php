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
   
    $nameCheck = preg_match('/^[a-z\d]{4,12}$/i',$_POST['name']);
    $emailCheck = preg_match('/^[a-z\d]+@[a-z]+\.[a-z]{2,3}(\.[a-z]{2})?$/',$_POST['email']);
     $phoneCheck = preg_match('/^09[\d]{9}$/',$_POST['phone']);
   
        
     
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address'])  || $nameCheck != 1 || $emailCheck != 1 || $phoneCheck != 1){
        if(empty($_POST['name'])){
            $nameError = "The name field is required";
        }elseif($nameCheck != 1){
            $nameError = "Username must be alphanumeric and contain 4-12 characters";
        }
    
        if(empty($_POST['email'])){
            $emailError = "The email field is required";
        }elseif($emailCheck != 1){
            $emailError = "Email must be a valid address.e.g. me@mydomain.com";
        }
    
        if(empty($_POST['phone'])){
            $phoneError = "The phone field is required";
        }elseif($phoneCheck != 1){
            $phoneError = "Phone number must be a valid Myanmar telephone number (11 digits).e.g.09123456789";
        }
    
        if(empty($_POST['address'])){
            $addError = "The address field is required";
        }
       
 

    }else if(!empty($_POST['password']) && preg_match('/^[\w@-]{8,20}$/',$_POST['password']) != 1){
        $passError = "Password must alphanumeric (@,_ and - are also allowed) and be 8-20 characters ";
    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $updated_at = date('Y-m-d H:i:s',time());
      
      
        if(empty($_POST['role'])){
            $role = 0;
        }else{
            $role = 1;
        }
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id != :id");
    
       
        $stmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
          echo "<script>alert('Email duplicated')</script>";
        }else{
            if($password != null){
                if($_FILES['image']['name'] != null){
                    $fileName = $_FILES['image']['name'];
                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));
                    $allowed = ['jpg','jpeg','png'];
                    if(in_array($fileActualExt,$allowed)){
                       $image =    uniqid('',true) . "." . $fileName;
                        move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$image);
                        $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,phone=:phone,address=:address,password=:password,role=:role,image=:image,updated_at=:update WHERE id = :id");
                       $result = $stmt->execute([
                            ':id' => $id,
                            ':name' => $name,
                            ':email' => $email,
                            ':phone' => $phone,
                            ':address' => $address,
                            ':password' => $password,
                            ':role' => $role,
                            ':image' => $image,
                            ':update' => $updated_at
                
                        ]);
                        if($result){
                            echo "<script>alert('Successfully Updated');window.location.href='user_list.php'</script>";
                        }
                        
                    }
                }else{
                    $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,phone=:phone,address=:address,password=:password,role=:role,updated_at=:update WHERE id = :id");
                    $result = $stmt->execute([
                        ':id' => $id,
                         ':name' => $name,
                         ':email' => $email,
                         ':phone' => $phone,
                         ':address' => $address,
                         ':password' => $password,
                         ':role' => $role,
                         ':update' => $updated_at
                     ]);
                     if($result){
                         echo "<script>alert('Successfully Updated');window.location.href='user_list.php'</script>";
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
                        $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,phone=:phone,address=:address,role=:role,image=:image,updated_at=:update WHERE id = :id");
                       $result = $stmt->execute([
                            ':id' => $id,
                            ':name' => $name,
                            ':email' => $email,
                            ':phone' => $phone,
                            ':address' => $address,
                            ':role' => $role,
                            ':image' => $image,
                            ':update' => $updated_at
                
                        ]);
                        if($result){
                            echo "<script>alert('Successfully Updated');window.location.href='user_list.php'</script>";
                        }
                        
                    }
                }else{
                    $stmt = $pdo->prepare("UPDATE users SET name=:name,email=:email,phone=:phone,address=:address,role=:role,updated_at=:update WHERE id = :id");
                    $result = $stmt->execute([
                        ':id' => $id,
                         ':name' => $name,
                         ':email' => $email,
                         ':phone' => $phone,
                         ':address' => $address,
                         ':role' => $role,
                         ':update' => $updated_at
                     ]);
                     if($result){
                         echo "<script>alert('Successfully Added');window.location.href='user_list.php'</script>";
                     }
                }
            }
    
                
            
       
     
        }
    }

    }



if(isset($_GET['id'])){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch();
   

}



?>
<?php include "header.php" ?>
    <main id="main" class="main">

        <section class="section">
            <div class="container">
            <h3>Users Edit</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                    <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control <?php if(!empty($nameError)){ ?>is-invalid<?php } ?>" id="name" value="<?php echo $result['name'] ?>">
                        <span style="color:red"><?php echo empty($nameError) ?  "" : $nameError  ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?php if(!empty($emailError)){ ?>is-invalid<?php } ?>" id="email" value="<?php echo $result['email'] ?>">
                        <span style="color:red"><?php echo empty($emailError) ?  "" : $emailError  ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="number" name="phone" class="form-control <?php if(!empty($phoneError)){ ?>is-invalid<?php } ?>" id="phone" value="<?php echo $result['phone'] ?>">
                        <span style="color:red"><?php echo empty($phoneError) ?  "" : $phoneError ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text"  name="address" class="form-control <?php if(!empty($addError)){ ?>is-invalid<?php } ?>" id="address" value="<?php echo $result['address'] ?>">
                        <span style="color:red"><?php echo empty($addError) ?  "" : $addError ?></span>
                    </div>
                     <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"  name="password" class="form-control <?php if(!empty($passError)){ ?>is-invalid<?php } ?>" id="password" >
                        <span style="font-size:10px ;">The user already has a password</span>
                        <span style="color:red"><?php echo empty($passError) ?  "" : $passError ?></span>
                       
                    </div>
                    <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="checkbox"  name="role" <?php if($result['role'] == 1) : ?>checked<?php endif ?>>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                        <?php if($result['image'] == null) { ?><img src="dummy/OIP (1).jpg" alt="" style="width:100px;height:100px"><?php }else{ ?>             <img src="images/<?php echo $result['image'] ?>" alt="" style="width:100px;height:100px" class="my-3"><?php } ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Update</button>
                    <a href="user_list.php" class="btn btn-warning">Back</a>
                </form>
            </div>
         


        </section>

    </main><!-- End #main -->
    <?php include "footer.php" ?>