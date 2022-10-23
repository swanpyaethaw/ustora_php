-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2022 at 09:39 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ustora`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'phone', 'phone', '2022-10-12 20:47:57', '2022-10-12 20:47:57'),
(2, 'laptop', 'laptop', '2022-10-13 10:35:54', '2022-10-13 10:35:54'),
(3, ' Tv', 'Tv', '2022-10-13 11:44:22', '2022-10-13 11:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `discount` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `image` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category_id`, `price`, `discount`, `quantity`, `image`, `created_at`, `updated_at`) VALUES
(1, 'huawei', 'huawei', 1, 500, 1000, 3, '6347ccfed1c355.41076640.product-2.jpg', '2022-10-12 20:48:37', '2022-10-12 20:48:37'),
(2, 'nokia', 'nokia', 1, 400, 1000, 4, '6347cc723d9600.85711514.product-4.jpg', '2022-10-13 10:34:38', '2022-10-13 10:34:38'),
(3, 'iphone', 'iphone', 1, 1000, 1200, 2, '63478e84a16f78.88258141.product-5.jpg', '2022-10-13 10:35:24', '2022-10-13 10:35:24'),
(4, 'dell', 'dell', 2, 600, 1000, 4, '63478ed54987c8.18438468.product-thumb-3.jpg', '2022-10-13 10:36:45', '2022-10-13 10:36:45'),
(5, 'redmi', 'redmi', 1, 300, 1000, 4, '6347ffa8e6e251.33323721.product-thumb-2.jpg', '2022-10-13 11:21:30', '2022-10-13 11:21:30'),
(6, 'Sony Tv', 'Sony', 3, 900, 1000, 0, '63479ee811c7d5.50528371.product-thumb-4.jpg', '2022-10-13 11:45:20', '2022-10-13 11:45:20'),
(7, 'dell01', 'dell', 2, 700, 800, 3, '63478ed54987c8.18438468.product-thumb-3.jpg', '2022-10-13 10:36:45', '2022-10-13 10:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_review`
--

CREATE TABLE `product_review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_review`
--

INSERT INTO `product_review` (`id`, `user_id`, `product_id`, `review`, `rating`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'good', NULL, '2022-10-17 18:23:18', '2022-10-17 18:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `sale_orders`
--

CREATE TABLE `sale_orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sale_orders`
--

INSERT INTO `sale_orders` (`id`, `customer_id`, `total_amount`, `order_date`) VALUES
(1, 1, 500, '2022-10-12 18:55:45'),
(2, 1, 700, '2022-10-14 13:52:26'),
(3, 1, 900, '2022-10-14 13:55:33'),
(4, 2, 400, '2022-10-14 17:45:21'),
(5, 6, 900, '2022-10-15 17:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_detail`
--

CREATE TABLE `sale_order_detail` (
  `id` int(11) NOT NULL,
  `sale_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sale_order_detail`
--

INSERT INTO `sale_order_detail` (`id`, `sale_order_id`, `product_id`, `quantity`, `order_date`) VALUES
(1, 1, 1, 5, '2022-10-12 19:27:26'),
(2, 1, 2, 4, '2022-10-13 12:09:29'),
(3, 2, 3, 4, '2022-10-13 12:09:29'),
(4, 2, 4, 5, '2022-10-13 12:11:44'),
(5, 2, 7, 1, '2022-10-14 13:52:26'),
(6, 3, 6, 1, '2022-10-14 13:55:33'),
(7, 4, 2, 1, '2022-10-14 17:45:21'),
(8, 5, 6, 1, '2022-10-15 17:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `password`, `image`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '09123456789', 'Ygn', '$2y$10$BEs5fodis1mHmoA33UJZA.ue8BK190q7Wsd5JfQzAus.wNU0kIM8S', '6348373a2bc992.51395001.User Avatar-2.png', 1, '2022-10-13 22:35:14', '2022-10-13 22:35:14'),
(2, 'mgmg', 'mgmg@gmail.com', '09250138831', 'YGN', '$2y$10$iDQmgVR4/zppuAACtH57Ue3d6Hvt4kSzLtf78C5FM01oU8mFygBL.', NULL, 0, '2022-10-13 22:37:43', '2022-10-13 22:37:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_review`
--
ALTER TABLE `product_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_orders`
--
ALTER TABLE `sale_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_order_detail`
--
ALTER TABLE `sale_order_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_review`
--
ALTER TABLE `product_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sale_orders`
--
ALTER TABLE `sale_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sale_order_detail`
--
ALTER TABLE `sale_order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
