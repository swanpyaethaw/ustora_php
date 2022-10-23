
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Tables / General - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">NiceAdmin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <?php 
        $link = $_SERVER['PHP_SELF'];
        $linkArray = explode('/',$link);
        $page = end($linkArray);
        ?>

        <?php if($page === "index.php" || $page === "user_list.php" || $page === "category_list.php" || $page == "weekly_report.php" || $page == "weekly_report.php" || $page == "best_selling_products.php" || $page == "best_selling_products.php") : ?>
        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" 
            <?php if($page === "index.php") : ?>
                action = "index.php"
                <?php endif ?>
                <?php if($page === "user_list.php") : ?>
                action = "user_list.php"
                <?php endif ?>
                <?php if($page === "category_list.php") : ?>
                action = "category_list.php"
                <?php endif ?>
                <?php if($page === "weekly_report.php") : ?>
                action = "weekly_report.php"
                <?php endif ?>
                <?php if($page === "monthly_report.php") : ?>
                action = "monthly_report.php"
                <?php endif ?>
                <?php if($page === "best_selling_products.php") : ?>
                action = "best_selling_products.php"
                <?php endif ?>
                <?php if($page === "royal_customers.php") : ?>
                action = "royal_customers.php"
                <?php endif ?>
                >
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                <input type="text"  name="search" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->
        <?php endif ?>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <?php if($_SESSION['user_image'] == null) { ?>
                        <img src="dummy/OIP (1).jpg" alt="Profile" class="rounded-circle">
                        <?php }else{ ?>
                            <img src="images/<?php echo $_SESSION['user_image'] ?>" alt="Profile" class="rounded-circle">
                            <?php } ?>
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['user_name'] ?></span>
                    </a><!-- End Profile Iamge Icon -->


                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Lists</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="index.php" class="active">
                            <i class="bi bi-circle"></i><span>Products List</span>
                        </a>
                    </li>
                    <li>
                        <a href="category_list.php" class="active">
                            <i class="bi bi-circle"></i><span>Category List</span>
                        </a>
                    </li>
                    <li>
                        <a href="user_list.php" class="active">
                            <i class="bi bi-circle"></i><span>Users List</span>
                        </a>
                    </li>
                    <li>
                        <a href="order_list.php" class="active">
                            <i class="bi bi-circle"></i><span>Sale Order List</span>
                        </a>
                    </li>
                    <li>
                        <a href="weekly_report.php" class="active">
                            <i class="bi bi-circle"></i><span>Weekly Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="monthly_report.php" class="active">
                            <i class="bi bi-circle"></i><span>Monthly Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="best_selling_products.php" class="active">
                            <i class="bi bi-circle"></i><span>Best Selling Products</span>
                        </a>
                    </li>
                     <li>
                        <a href="royal_customers.php" class="active">
                            <i class="bi bi-circle"></i><span>Royal Customers</span>
                        </a>
                    </li>

                </ul>
            </li><!-- End Tables Nav -->

        </ul>

    </aside><!-- End Sidebar-->