
<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ustora Demo</title>
    
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
   
    <div class="header-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="user-menu">
                    <?php if(!empty($_SESSION['user_id'])) : ?>
                        <ul>
                            <li><a href="logout.php"><i class="fa fa-user"></i>Logout</a></li>
                        </ul>
                        <?php endif ?>
                        <?php if(empty($_SESSION['user_id'])) : ?>
                        <ul>
                            <li><a href="register.php"><i class="fa fa-user"></i> Register</a></li>
                            <li><a href="login.php"><i class="fa fa-user"></i> Login</a></li>
                        </ul>
                        <?php endif ?>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div> <!-- End header area -->
    
    <div class="site-branding-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="logo">
                        <h1><a href="./"><img src="img/logo.png"></a></h1>
                    </div>
                </div>
               
                <?php 
                $cart = 0;
                $total = 0;

                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $key=>$qty){
                        $id = str_replace('id','',$key);
                        $cartStmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
                        $cartStmt->execute();
                        $cartResult = $cartStmt->fetch();
                        $total += $cartResult['price'] * $qty;
                        $cart += $qty;
                    }
                }
                
                
                ?>
                 <?php if(!empty($_SESSION['user_id'])) : ?>
                <div class="col-sm-6">
                    <div class="shopping-item">
                        <a href="cart.php">Cart - <span class="cart-amunt"><?php echo $total ?></span> <i class="fa fa-shopping-cart"></i> <span class="product-count"><?php echo $cart ?></span></a>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div> <!-- End site branding area -->
    
    <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> 
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav mr-auto">
                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="shop.php">Shop page</a></li>
                        <li><a href="cart.php">Cart</a></li>
                    </ul>
                    <?php 

                        $link = $_SERVER['PHP_SELF'];
                        $linkArray = explode('/',$link);
                        $page = end($linkArray);
                    
                    ?>

                   <?php if($page == "shop.php" || $page == "top_seller_view.php" || $page == "top_new.php") : ?>
                    <form class="form-inline" action="shop.php" method="POST">
                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
      <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <?php endif ?>
                </div>  
            </div>
        </div>
    </div> <!-- End mainmenu area -->