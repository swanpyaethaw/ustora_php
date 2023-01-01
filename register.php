<?php 

session_start();
require "config/config.php";
require "config/common.php";

if(isset($_POST['submit'])){
 
    $nameCheck = preg_match('/^[a-z\d]{4,12}$/i',$_POST['name']);
    $emailCheck = preg_match('/^[a-z\d]+@[a-z]+\.[a-z]{2,4}(\.[a-z]{2})?$/',$_POST['email']);
    $phoneCheck = preg_match('/^09[\d]{9}$/',$_POST['phone']);
    $passCheck = preg_match('/^[\w@-]{8,20}$/',$_POST['password']);


    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['password']) || $nameCheck != 1 || $emailCheck != 1 || $passCheck != 1 || $phoneCheck != 1){
        if(empty($_POST['name'])){
            $nameError = "The name field is required";
        }else{
       
          if($nameCheck != 1){
            $nameError = "Username must be alphanumeric and contain 4-12 characters";
          }
        }
    
        if(empty($_POST['email'])){
            $emailError = "The email field is required";
        }else{
         
            if($emailCheck != 1){
                $emailError = "Email must be a valid address.e.g. me@mydomain.com";
            }
    
           
        }
    
        if(empty($_POST['phone'])){
            $phoneError = "The phone field is required";
        }else{
            
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
         
            if($passCheck != 1){
                $passError = "Password must alphanumeric (@,_ and - are also allowed) and be 8-20 characters ";
            }
          
        }

}else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

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
                move_uploaded_file($_FILES['image']['tmp_name'],'admin/images/'.$image);
                $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,address,password,image) VALUES (:name,:email,:phone,:address,:password,:image)");
               $result = $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':password' => $password,
                    ':image' => $image,
        
                ]);
                if($result){
                    echo "<script>alert('Successfully Register);window.location.href='login.php'</script>";
                }
                
            }
        }else{

            $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,address,password) VALUES (:name,:email,:phone,:address,:password)");
            $result = $stmt->execute([
                 ':name' => $name,
                 ':email' => $email,
                 ':phone' => $phone,
                 ':address' => $address,
                 ':password' => $password,
              
             ]);
             if($result){
                 echo "<script>alert('Successfully Register');window.location.href='login.php'</script>";
             }
        }
    }
    
 
    }
}



?>

<!DOCTYPE html>
<html lang="zxx" class="no-js2">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Karma Shop</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css2/linearicons.css">
	<link rel="stylesheet" href="css2/owl.carousel.css">
	<link rel="stylesheet" href="css2/themify-icons.css">
	<link rel="stylesheet" href="css2/font-awesome.min.css">
	<link rel="stylesheet" href="css2/nice-select.css">
	<link rel="stylesheet" href="css2/nouislider.min.css">
	<link rel="stylesheet" href="css2/bootstrap.css">
	<link rel="stylesheet" href="css2/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.php"><img src="img2/logo.png" alt=""></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->



	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap mt-5">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-12">
					<div class="login_form_inner">
						<h3>Register Account</h3>
						<form class="row login_form" action="register.php" method="post" id="contactForm" novalidate="novalidate" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                            <div class="col-md-12 form-group">
								<input type="text" class="form-control <?php if(!empty($nameError)){ ?>is-invalid<?php } ?>" id="name"  name="name" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
                                <span style="color:red"><?php echo empty($nameError) ?  "" : $nameError  ?></span>
							</div>
							<div class="col-md-12 form-group">
								<input type="email" class="form-control <?php if(!empty($emailError)){ ?>is-invalid<?php } ?>" id="email"  name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
                                <span style="color:red"><?php echo empty($emailError) ?  "" : $emailError  ?></span>
							</div>
                            <div class="col-md-12 form-group">
								<input type="number" class="form-control <?php if(!empty($phoneError)){ ?>is-invalid<?php } ?>" id="phone"  name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
                                <span style="color:red"><?php echo empty($phoneError) ?  "" : $phoneError ?></span>
							</div>
                            <div class="col-md-12 form-group">
                                <textarea name="address" id="address" class="form-control <?php if(!empty($addError)){ ?>is-invalid<?php } ?>" cols="10" rows="5" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'"></textarea>
                                <span style="color:red"><?php echo empty($addError) ?  "" : $addError ?></span>
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control <?php if(!empty($passError)){ ?>is-invalid<?php } ?>" id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" >
                                <span style="color:red"><?php echo empty($passError) ?  "" : $passError ?></span>
							</div>
                            <div class="col-md-12 form-group">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                            </div>
					
							<div class="col-md-12 form-group">
								<button type="submit" name="submit"  class="primary-btn">Register</button>
							</div>
                           
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>About Us</h6>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore dolore
							magna aliqua.
						</p>
					</div>
				</div>
				<div class="col-lg-4  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>Newsletter</h6>
						<p>Stay update with our latest</p>
						<div class="" id="mc_embed_signup">

							<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
							 method="get" class="form-inline">

								<div class="d-flex flex-row">

									<input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '"
									 required="" type="email">


									<button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
									<div style="position: absolute; left: -5000px;">
										<input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
									</div>

									<!-- <div class="col-lg-4 col-md-4">
													<button class="bb-btn btn"><span class="lnr lnr-arrow-right"></span></button>
												</div>  -->
								</div>
								<div class="info"></div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget mail-chimp">
						<h6 class="mb-20">Instragram Feed</h6>
						<ul class="instafeed d-flex flex-wrap">
							<li><img src="img2/i1.jpg" alt=""></li>
							<li><img src="img2/i2.jpg" alt=""></li>
							<li><img src="img2/i3.jpg" alt=""></li>
							<li><img src="img2/i4.jpg" alt=""></li>
							<li><img src="img2/i5.jpg" alt=""></li>
							<li><img src="img2/i6.jpg" alt=""></li>
							<li><img src="img2/i7.jpg" alt=""></li>
							<li><img src="img2/i8.jpg" alt=""></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>Follow Us</h6>
						<p>Let us be social</p>
						<div class="footer-social d-flex align-items-center">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-dribbble"></i></a>
							<a href="#"><i class="fa fa-behance"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->


	<script src="js2/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js2/vendor/bootstrap.min.js"></script>
	<script src="js2/jquery.ajaxchimp.min.js"></script>
	<script src="js2/jquery.nice-select.min.js"></script>
	<script src="js2/jquery.sticky.js"></script>
	<script src="js2/nouislider.min.js"></script>
	<script src="js2/jquery.magnific-popup.min.js"></script>
	<script src="js2/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js2/gmaps.min.js"></script>
	<script src="js2/main.js"></script>
</body>

</html>