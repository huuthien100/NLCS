-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2023 at 01:36 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gundam_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `id_product`, `quantity`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(23, 6, 66, 1, 580000, 580000, '2023-10-29 11:12:24', '2023-10-29 11:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL,
  `name_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_category`, `name_category`) VALUES
(1, 'HG'),
(2, 'RG'),
(3, 'MG'),
(4, 'PG'),
(5, 'SD'),
(6, 'MB'),
(7, 'Tool'),
(8, 'Base');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `shipping_address`, `total_price`, `status`) VALUES
(18, 6, '2023-10-29', 'asd', 5110000, 'Đã xác nhận'),
(24, 6, '2023-10-29', 'test', 1160000, 'Chờ xác nhận'),
(25, 6, '2023-10-29', 'test 2', 730000, 'Chờ xác nhận');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_item_id`, `order_id`, `id_product`, `quantity`) VALUES
(18, 18, 69, 7),
(22, 24, 63, 2),
(23, 25, 69, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_img` varchar(255) NOT NULL,
  `product_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `id_category`, `product_name`, `product_img`, `product_price`) VALUES
(61, 1, 'Aerial Gundam', '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-1.jpg', '700000'),
(63, 1, 'Barbatos Lupus Rex Gundam', '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-1.jpg', '580000'),
(64, 1, 'Build Strike Full Package Gundam', '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-1.jpg', '670000'),
(65, 1, 'Destiny Gundam', '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-1.jpg', '650000'),
(66, 1, 'LFRITH Gundam', '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-1.jpg', '580000'),
(67, 1, 'V2 Assault Buster Gundam', '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-1.jpg', '590000'),
(69, 2, 'Aile Strike Gundam', '../asset/product/RG/RG-Aile Strike Gundam/Aile Strike Gundam-1.jpg', '730000');

-- --------------------------------------------------------

--
-- Table structure for table `product_detail`
--

CREATE TABLE `product_detail` (
  `id` int(11) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `scale` varchar(20) DEFAULT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `equipment` varchar(255) DEFAULT NULL,
  `decal` varchar(255) DEFAULT NULL,
  `stand` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_detail`
--

INSERT INTO `product_detail` (`id`, `id_product`, `scale`, `detail`, `equipment`, `decal`, `stand`, `origin`, `description`) VALUES
(1, 64, 'HG - 1/144', 'Vừa phải, khớp chuyển động tương đối linh hoạt. Ráp theo kiểu bấm khớp, không cần dùng keo dán.', 'Beam Rifle, Beam Gun, Rifle được chỉnh sửa, khiên chắn, 2 Beam Saber, 2 Cannon.', 'Đính kèm decal dán.', 'Không kèm đế dựng.', 'Sản phẩm Gunpla chính hãng của Bandai, sản xuất tại Nhật Bản.', 'GAT-X105B/FP Build Strike Gundam Full Package ( HGBF - 1/144) là Gunpla xuất hiện trong series anime Gundam Build Fighters.\r\nMẫu Gunpla này được build bởi Sei Iori và điều khiển bởi Reiji.'),
(2, 61, '', '', 'asd', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_img`
--

CREATE TABLE `product_img` (
  `img_id` int(11) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `img_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_img`
--

INSERT INTO `product_img` (`img_id`, `id_product`, `img_url`) VALUES
(134, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-1.jpg'),
(135, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-2.jpg'),
(136, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-3.jpg'),
(137, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-4.jpg'),
(138, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-5.jpg'),
(139, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-6.jpg'),
(140, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-7.jpg'),
(141, 63, '../asset/product/HG/HG-Barbatos Lupus Rex Gundam/Barbatos Lupus Rex Gundam-8.jpg'),
(150, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-1.jpg'),
(151, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-2.jpg'),
(152, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-3.jpg'),
(153, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-4.jpg'),
(154, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-5.jpg'),
(155, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-6.jpg'),
(156, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-7.jpg'),
(157, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-8.jpg'),
(158, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-9.jpg'),
(159, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-10.jpg'),
(160, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-11.jpg'),
(161, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-12.jpg'),
(162, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-13.jpg'),
(163, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-14.jpg'),
(164, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-15.jpg'),
(165, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-16.jpg'),
(166, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-17.jpg'),
(167, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-18.jpg'),
(168, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-19.jpg'),
(169, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-20.jpg'),
(170, 65, '../asset/product/HG/HG-Destiny Gundam/Destiny Gundam-21.jpg'),
(172, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-2.jpg'),
(173, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-3.jpg'),
(174, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-4.jpg'),
(175, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-5.jpg'),
(176, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-6.jpg'),
(177, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-7.jpg'),
(178, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-8.jpg'),
(179, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-9.jpg'),
(180, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-10.jpg'),
(181, 66, '../asset/product/HG/HG-LFRITH Gundam/LFRITH Gundam-11.jpg'),
(182, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-1.jpg'),
(183, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-2.jpg'),
(184, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-3.jpg'),
(185, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-4.jpg'),
(186, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-5.jpg'),
(187, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-6.jpg'),
(188, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-7.jpg'),
(189, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-8.jpg'),
(190, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-9.jpg'),
(191, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-10.jpg'),
(192, 67, '../asset/product/HG/HG-V2 Assault Buster Gundam/V2 Assault Buster Gundam-11.jpg'),
(194, 69, '../asset/product/RG/RG-Aile Strike Gundam/Aile Strike Gundam-1.jpg'),
(345, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-1.jpg'),
(346, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-2.jpg'),
(347, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-3.jpg'),
(348, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-4.jpg'),
(349, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-5.jpg'),
(350, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-6.jpg'),
(351, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-7.jpg'),
(352, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-8.jpg'),
(353, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-9.jpg'),
(354, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-10.jpg'),
(355, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-11.jpg'),
(356, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-12.jpg'),
(357, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-13.jpg'),
(358, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-14.jpg'),
(359, 61, '../asset/product/HG/HG-Aerial Gundam/Aerial Gundam-15.jpg'),
(368, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-1.jpg'),
(369, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-2.jpg'),
(370, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-3.jpg'),
(371, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-4.jpg'),
(372, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-5.jpg'),
(373, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-6.jpg'),
(374, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-7.jpg'),
(375, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-8.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `access`) VALUES
(6, 'admin', 'admin@admin', '$2y$10$7sEOt0opToeC9OYzG8lTL.gdqgS2LOctVKhOD/LoT4ie7yBqnhMzi', 0),
(7, 'user', 'user@user', '$2y$10$1dK4XZ/KtIeHiqAMuzVKWuaCfuSncrK5kQBsoGGjfVzpg6vObPO5e', 1),
(9, 'test', 'test123@test', '$2y$10$DBIaaCjJx8/qbqus5EeDEO2Vfzdx7T7.h6eVZJgkMMGG9dCJv0YZm', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `fk_category_product` (`id_category`);

--
-- Indexes for table `product_detail`
--
ALTER TABLE `product_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `product_img`
--
ALTER TABLE `product_img`
  ADD PRIMARY KEY (`img_id`),
  ADD KEY `fk_product_id` (`id_product`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `product_detail`
--
ALTER TABLE `product_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_img`
--
ALTER TABLE `product_img`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=376;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category_product` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`);

--
-- Constraints for table `product_detail`
--
ALTER TABLE `product_detail`
  ADD CONSTRAINT `product_detail_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Constraints for table `product_img`
--
ALTER TABLE `product_img`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
