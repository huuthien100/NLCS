-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2023 at 06:25 PM
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
(42, 6, 63, 3, 580000, 1740000, '2023-11-12 14:26:55', '2023-11-12 14:26:55');

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
  `order_date` datetime DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `shipping_address`, `total_price`, `status`) VALUES
(37, 6, '2023-11-10 20:46:13', 'Cần Thơ Việt Nam', 14770000, 'Đã xác nhận'),
(38, 6, '2023-11-11 00:08:22', 'Rạch Gía Kiên Giang', 1740000, 'Chờ xác nhận'),
(39, 7, '2023-11-12 23:00:11', 'Cần Thơ', 8710000, 'Chờ xác nhận');

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
(36, 37, 67, 1),
(37, 37, 74, 1),
(38, 37, 85, 1),
(39, 37, 88, 1),
(40, 37, 96, 1),
(41, 37, 102, 1),
(42, 37, 110, 1),
(43, 38, 66, 3),
(44, 39, 64, 13);

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
(70, 2, 'Aile Strike Gundam', '../asset/product/RG/RG-Aile Strike Gundam/Aile Strike Gundam-1.jpg', '730000'),
(71, 2, 'Destiny Gundam', '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-1.jpg', '770000'),
(72, 2, 'Force Impulse Gundam', '../asset/product/RG/RG-RG-Force Impulse Gundam/RG-Force Impulse Gundam-1.jpg', '780000'),
(73, 2, 'Freedom Gundam', '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-1.jpg', '760000'),
(74, 2, 'RX-93 Nu V Gundam', '../asset/product/RG/RG-RX-93 Nu V Gundam (1)/RX-93 Nu V Gundam (1)-1.jpg', '1230000'),
(75, 2, 'RG-Zaku II', '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-1.jpg', '700000'),
(76, 3, 'Avalanche Exia Dash Gundam', '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-1.jpg', '1850000'),
(78, 3, 'Buster Gundam', '../asset/product/MG/MG-Buster Gundam/Buster Gundam-1.jpg', '900000'),
(79, 3, 'Eclipse Gundam', '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-1.jpg', '1600000'),
(80, 3, 'F91 2.0 Gundam', '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-1.jpg', '1200000'),
(81, 3, 'Providence Gundam', '../asset/product/MG/MG-Providence Gundam/Providence Gundam-1.jpg', '1550000'),
(82, 4, '00 Seven Sword Gundam', '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-1.jpg', '7400000'),
(83, 4, 'Exia Gundam', '../asset/product/PG/PG-Exia Gundam/Exia Gundam-1.jpg', '5000000'),
(84, 4, 'RX-78-2 Gundam', '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-1.jpg', '850000'),
(85, 4, 'Strike Freedom Gundam', '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-1.jpg', '7000000'),
(86, 4, 'Unicorn Gundam', '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-1.jpg', '4800000'),
(87, 4, 'Wing Zero EW Gundam', '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-1.jpg', '3800000'),
(88, 5, '00 Gundam', '../asset/product/SD/SD-00 Gundam/00 Gundam-1.jpg', '250000'),
(89, 5, 'Destiny Gundam', '../asset/product/SD/SD-Destiny Gundam/Destiny Gundam-1.jpg', '250000'),
(90, 5, 'Exia Gundam', '../asset/product/SD/SD-Exia Gundam/Exia Gundam-1.jpg', '250000'),
(91, 5, 'Sinanju Gundam', '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-1.jpg', '250000'),
(92, 5, 'Try Burning Gundam', '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-1.jpg', '250000'),
(93, 5, 'Wing Zero EW Gundam', '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-1.jpg', '250000'),
(94, 6, 'Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)', '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-1.jpg', '4500000'),
(95, 6, 'Astray Red Frame Gundam', '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-1.jpg', '3100000'),
(96, 6, 'F91 Gundam', '../asset/product/MB/MB-F91 Gundam/F91 Gundam-1.jpg', '5500000'),
(97, 6, 'Freedom Gundam', '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-1.jpg', '4400000'),
(98, 6, 'Hi-Nu Gundam', '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-1.jpg', '6000000'),
(99, 6, 'Justice Gundam', '../asset/product/MB/MB-Justice Gundam/Justice Gundam-1.jpg', '4800000'),
(100, 7, 'Bình khí nén và Air Brush', '../asset/product/Tool/Tool-Bình khí nén và Air Brush/Bình khí nén và Air Brush-1.jpg', '3000000'),
(101, 7, 'Bộ dụng cụ cơ bản', '../asset/product/Tool/Tool-Bộ dụng cụ cơ bản/Bộ dụng cụ cơ bản-1.jpg', '250000'),
(102, 7, 'Gundam Cutting Mat', '../asset/product/Tool/Tool-Gundam Cutting Mat/Gundam Cutting Mat-1.jpg', '100000'),
(103, 7, 'Keo dán Tamiya Extra Thin', '../asset/product/Tool/Tool-Keo dán Tamiya Extra Thin/Keo dán Tamiya Extra Thin-1.jpg', '250000'),
(104, 7, 'Mực kẻ line đen', '../asset/product/Tool/Tool-Mực kẻ line đen/Mực kẻ line đen-1.jpg', '100000'),
(105, 7, 'Mực kẻ line trắng', '../asset/product/Tool/Tool-Mực kẻ line trắng/Mực kẻ line trắng-1.jpg', '100000'),
(106, 7, 'Sơn màu Tamiya', '../asset/product/Tool/Tool-Sơn màu Tamiya/Sơn màu Tamiya-1.jpg', '100000'),
(107, 7, 'Sơn Topcoat', '../asset/product/Tool/Tool-Sơn Topcoat/Sơn Topcoat-1.jpg', '150000'),
(108, 7, 'Tamiya Thinner', '../asset/product/Tool/Tool-Tamiya Thinner/Tamiya Thinner-1.jpg', '200000'),
(109, 8, 'Action Base 5 Black', '../asset/product/Base/Base-Action Base 5 Black/Action Base 5 Black-1.jpg', '100000'),
(110, 8, 'Giá đỡ mô hình Model Effect', '../asset/product/Base/Base-Giá đỡ mô hình Model Effect/Giá đỡ mô hình Model Effect-1.png', '100000'),
(111, 8, 'Gundam Mechanical Base', '../asset/product/Base/Base-Gundam Mechanical Base/Gundam Mechanical Base-1.jpg', '250000'),
(112, 8, 'Hộp trưng bày Gundam có Led', '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-1.jpg', '350000'),
(113, 3, 'Banshee Gundam', '../asset/product/MG/MG-Banshee Gundam/Banshee Gundam-1.jpg', '1300000');

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
(375, 64, '../asset/product/HG/HG-Build Strike Full Package Gundam/Build Strike Full Package Gundam-8.jpg'),
(376, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-1.jpg'),
(377, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-2.jpg'),
(378, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-3.jpg'),
(379, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-4.jpg'),
(380, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-5.jpg'),
(381, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-6.jpg'),
(382, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-7.jpg'),
(383, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-8.jpg'),
(384, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-9.jpg'),
(385, 70, '../asset/product/RG/RG-RG-Aile Strike Gundam/RG-Aile Strike Gundam-10.jpg'),
(386, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-1.jpg'),
(387, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-2.jpg'),
(388, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-3.jpg'),
(389, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-4.jpg'),
(390, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-5.jpg'),
(391, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-6.jpg'),
(392, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-7.jpg'),
(393, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-8.jpg'),
(394, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-9.jpg'),
(395, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-10.jpg'),
(396, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-11.jpg'),
(397, 71, '../asset/product/RG/RG-RG-Destiny Gundam/RG-Destiny Gundam-12.jpg'),
(398, 72, '../asset/product/RG/RG-RG-Force Impulse Gundam/RG-Force Impulse Gundam-1.jpg'),
(399, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-1.jpg'),
(400, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam (1)/RX-93 Nu V Gundam (1)-1.jpg'),
(401, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-1.jpg'),
(402, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-1.jpg'),
(404, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-1.jpg'),
(405, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-1.jpg'),
(406, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-1.jpg'),
(407, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-1.jpg'),
(408, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-1.jpg'),
(409, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-1.jpg'),
(410, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-1.jpg'),
(411, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-1.jpg'),
(412, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-1.jpg'),
(413, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-1.jpg'),
(414, 88, '../asset/product/SD/SD-00 Gundam/00 Gundam-1.jpg'),
(415, 89, '../asset/product/SD/SD-Destiny Gundam/Destiny Gundam-1.jpg'),
(416, 90, '../asset/product/SD/SD-Exia Gundam/Exia Gundam-1.jpg'),
(417, 91, '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-1.jpg'),
(418, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-1.jpg'),
(419, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-1.jpg'),
(420, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-1.jpg'),
(421, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-1.jpg'),
(422, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-1.jpg'),
(423, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-1.jpg'),
(424, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-1.jpg'),
(425, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-1.jpg'),
(426, 100, '../asset/product/Tool/Tool-Bình khí nén và Air Brush/Bình khí nén và Air Brush-1.jpg'),
(427, 101, '../asset/product/Tool/Tool-Bộ dụng cụ cơ bản/Bộ dụng cụ cơ bản-1.jpg'),
(428, 102, '../asset/product/Tool/Tool-Gundam Cutting Mat/Gundam Cutting Mat-1.jpg'),
(429, 103, '../asset/product/Tool/Tool-Keo dán Tamiya Extra Thin/Keo dán Tamiya Extra Thin-1.jpg'),
(430, 104, '../asset/product/Tool/Tool-Mực kẻ line đen/Mực kẻ line đen-1.jpg'),
(431, 105, '../asset/product/Tool/Tool-Mực kẻ line trắng/Mực kẻ line trắng-1.jpg'),
(432, 106, '../asset/product/Tool/Tool-Sơn màu Tamiya/Sơn màu Tamiya-1.jpg'),
(433, 107, '../asset/product/Tool/Tool-Sơn Topcoat/Sơn Topcoat-1.jpg'),
(434, 108, '../asset/product/Tool/Tool-Tamiya Thinner/Tamiya Thinner-1.jpg'),
(435, 109, '../asset/product/Base/Base-Action Base 5 Black/Action Base 5 Black-1.jpg'),
(436, 110, '../asset/product/Base/Base-Giá đỡ mô hình Model Effect/Giá đỡ mô hình Model Effect-1.png'),
(437, 111, '../asset/product/Base/Base-Gundam Mechanical Base/Gundam Mechanical Base-1.jpg'),
(438, 112, '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-1.jpg'),
(439, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-1.jpg'),
(440, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-2.jpg'),
(441, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-3.jpg'),
(442, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-4.jpg'),
(443, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-5.jpg'),
(444, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-6.jpg'),
(445, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-7.jpg'),
(446, 72, '../asset/product/RG/RG-Force Impulse Gundam/Force Impulse Gundam-8.jpg'),
(447, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-2.jpg'),
(448, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-3.jpg'),
(449, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-4.jpg'),
(450, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-5.jpg'),
(451, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-6.jpg'),
(452, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-7.jpg'),
(453, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-8.jpg'),
(454, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-9.jpg'),
(455, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-10.jpg'),
(456, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-11.jpg'),
(457, 73, '../asset/product/RG/RG-Freedom Gundam/Freedom Gundam-12.jpg'),
(458, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-1.jpg'),
(459, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-2.jpg'),
(460, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-3.jpg'),
(461, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-4.jpg'),
(462, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-5.jpg'),
(463, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-6.jpg'),
(464, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-7.jpg'),
(465, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-8.jpg'),
(466, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-9.jpg'),
(467, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-10.jpg'),
(468, 74, '../asset/product/RG/RG-RX-93 Nu V Gundam/RX-93 Nu V Gundam-11.jpg'),
(469, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-2.jpg'),
(470, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-3.jpg'),
(471, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-4.jpg'),
(472, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-5.jpg'),
(473, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-6.jpg'),
(474, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-7.jpg'),
(475, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-8.jpg'),
(476, 75, '../asset/product/RG/RG-RG-Zaku II/RG-Zaku II-9.jpg'),
(477, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-2.jpg'),
(478, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-3.jpg'),
(479, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-4.jpg'),
(480, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-5.jpg'),
(481, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-6.jpg'),
(482, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-7.jpg'),
(483, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-8.jpg'),
(484, 76, '../asset/product/MG/MG-Avalanche Exia Dash Gundam/Avalanche Exia Dash Gundam-9.jpg'),
(492, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-2.jpg'),
(493, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-3.jpg'),
(494, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-4.jpg'),
(495, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-5.jpg'),
(496, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-6.jpg'),
(497, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-7.jpg'),
(498, 78, '../asset/product/MG/MG-Buster Gundam/Buster Gundam-8.jpg'),
(499, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-2.jpg'),
(500, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-3.jpg'),
(501, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-4.jpg'),
(502, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-5.jpg'),
(503, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-6.jpg'),
(504, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-7.jpg'),
(505, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-8.jpg'),
(506, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-9.jpg'),
(507, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-10.jpg'),
(508, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-11.jpg'),
(509, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-12.jpg'),
(510, 79, '../asset/product/MG/MG-Eclipse Gundam/Eclipse Gundam-13.jpg'),
(511, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-2.jpg'),
(512, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-3.jpg'),
(513, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-4.jpg'),
(514, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-5.jpg'),
(515, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-6.jpg'),
(516, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-7.jpg'),
(517, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-8.jpg'),
(518, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-9.jpg'),
(519, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-10.jpg'),
(520, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-11.jpg'),
(521, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-12.jpg'),
(522, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-13.jpg'),
(523, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-14.jpg'),
(524, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-15.jpg'),
(525, 80, '../asset/product/MG/MG-F91 2.0 Gundam/F91 2.0 Gundam-16.jpg'),
(526, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-2.jpg'),
(527, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-3.jpg'),
(528, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-4.jpg'),
(529, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-5.jpg'),
(530, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-6.jpg'),
(531, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-7.jpg'),
(532, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-8.jpg'),
(533, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-9.jpg'),
(534, 81, '../asset/product/MG/MG-Providence Gundam/Providence Gundam-10.jpg'),
(535, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-2.jpg'),
(536, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-3.jpg'),
(537, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-4.jpg'),
(538, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-5.jpg'),
(539, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-6.jpg'),
(540, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-7.jpg'),
(541, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-8.jpg'),
(542, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-9.jpg'),
(543, 82, '../asset/product/PG/PG-00 Seven Sword Gundam/00 Seven Sword Gundam-10.jpg'),
(544, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-2.jpg'),
(545, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-3.jpg'),
(546, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-4.jpg'),
(547, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-5.jpg'),
(548, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-6.jpg'),
(549, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-7.jpg'),
(550, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-8.jpg'),
(551, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-9.jpg'),
(552, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-10.jpg'),
(553, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-11.jpg'),
(554, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-12.jpg'),
(555, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-13.jpg'),
(556, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-14.jpg'),
(557, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-15.jpg'),
(558, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-16.jpg'),
(559, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-17.jpg'),
(560, 83, '../asset/product/PG/PG-Exia Gundam/Exia Gundam-18.jpg'),
(561, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-2.jpg'),
(562, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-3.jpg'),
(563, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-4.jpg'),
(564, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-5.jpg'),
(565, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-6.jpg'),
(566, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-7.jpg'),
(567, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-8.jpg'),
(568, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-9.jpg'),
(569, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-10.jpg'),
(570, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-11.jpg'),
(571, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-12.jpg'),
(572, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-13.jpg'),
(573, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-14.jpg'),
(574, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-15.jpg'),
(575, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-16.jpg'),
(576, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-17.jpg'),
(577, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-18.jpg'),
(578, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-19.jpg'),
(579, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-20.jpg'),
(580, 84, '../asset/product/PG/PG-RX-78-2 Gundam/RX-78-2 Gundam-21.jpg'),
(581, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-2.jpg'),
(582, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-3.jpg'),
(583, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-4.jpg'),
(584, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-5.jpg'),
(585, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-6.jpg'),
(586, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-7.jpg'),
(587, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-8.jpg'),
(588, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-9.jpg'),
(589, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-10.jpg'),
(590, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-11.jpg'),
(591, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-12.jpg'),
(592, 85, '../asset/product/PG/PG-Strike Freedom Gundam/Strike Freedom Gundam-13.jpg'),
(593, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-2.jpg'),
(594, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-3.jpg'),
(595, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-4.jpg'),
(596, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-5.jpg'),
(597, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-6.jpg'),
(598, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-7.jpg'),
(599, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-8.jpg'),
(600, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-9.jpg'),
(601, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-10.jpg'),
(602, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-11.jpg'),
(603, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-12.jpg'),
(604, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-13.jpg'),
(605, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-14.jpg'),
(606, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-15.jpg'),
(607, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-16.jpg'),
(608, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-17.jpg'),
(609, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-18.jpg'),
(610, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-19.jpg'),
(611, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-20.jpg'),
(612, 86, '../asset/product/PG/PG-Unicorn Gundam/Unicorn Gundam-21.jpg'),
(613, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-2.jpg'),
(614, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-3.jpg'),
(615, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-4.jpg'),
(616, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-5.jpg'),
(617, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-6.jpg'),
(618, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-7.jpg'),
(619, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-8.jpg'),
(620, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-9.jpg'),
(621, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-10.jpg'),
(622, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-11.jpg'),
(623, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-12.jpg'),
(624, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-13.jpg'),
(625, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-14.jpg'),
(626, 87, '../asset/product/PG/PG-Wing Zero EW Gundam/Wing Zero EW Gundam-15.jpg'),
(627, 88, '../asset/product/SD/SD-00 Gundam/00 Gundam-2.jpg'),
(628, 88, '../asset/product/SD/SD-00 Gundam/00 Gundam-3.jpg'),
(629, 88, '../asset/product/SD/SD-00 Gundam/00 Gundam-4.jpg'),
(630, 88, '../asset/product/SD/SD-00 Gundam/00 Gundam-5.jpg'),
(631, 89, '../asset/product/SD/SD-Destiny Gundam/Destiny Gundam-2.jpg'),
(632, 89, '../asset/product/SD/SD-Destiny Gundam/Destiny Gundam-3.jpg'),
(633, 89, '../asset/product/SD/SD-Destiny Gundam/Destiny Gundam-4.jpg'),
(634, 89, '../asset/product/SD/SD-Destiny Gundam/Destiny Gundam-5.jpg'),
(635, 90, '../asset/product/SD/SD-Exia Gundam/Exia Gundam-2.jpg'),
(636, 90, '../asset/product/SD/SD-Exia Gundam/Exia Gundam-3.jpg'),
(637, 90, '../asset/product/SD/SD-Exia Gundam/Exia Gundam-4.jpg'),
(638, 90, '../asset/product/SD/SD-Exia Gundam/Exia Gundam-5.jpg'),
(639, 90, '../asset/product/SD/SD-Exia Gundam/Exia Gundam-6.jpg'),
(640, 91, '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-2.jpg'),
(641, 91, '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-3.jpg'),
(642, 91, '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-4.jpg'),
(643, 91, '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-5.jpg'),
(644, 91, '../asset/product/SD/SD-Sinanju Gundam/Sinanju Gundam-6.jpg'),
(645, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-2.jpg'),
(646, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-3.jpg'),
(647, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-4.jpg'),
(648, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-5.jpg'),
(649, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-6.jpg'),
(650, 92, '../asset/product/SD/SD-Try Burning Gundam/Try Burning Gundam-7.jpg'),
(651, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-2.jpg'),
(652, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-3.jpg'),
(653, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-4.jpg'),
(654, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-5.jpg'),
(655, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-6.jpg'),
(656, 93, '../asset/product/SD/SD-Wing Zero EW Gundam/Wing Zero EW Gundam-7.jpg'),
(657, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-2.jpg'),
(658, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-3.jpg'),
(659, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-4.jpg'),
(660, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-5.jpg'),
(661, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-6.jpg'),
(662, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-7.jpg'),
(663, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-8.jpg'),
(664, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-9.jpg'),
(665, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-10.jpg'),
(666, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-11.jpg'),
(667, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-12.jpg'),
(668, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-13.jpg'),
(669, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-14.jpg'),
(670, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-15.jpg'),
(671, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-16.jpg'),
(672, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-17.jpg'),
(673, 94, '../asset/product/MB/MB-Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)/Astray Gold Frame Amatsu Mina Gundam (Princess of the sky Ver.)-18.jpg'),
(674, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-2.jpg'),
(675, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-3.jpg'),
(676, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-4.jpg'),
(677, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-5.jpg'),
(678, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-6.jpg'),
(679, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-7.jpg'),
(680, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-8.jpg'),
(681, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-9.jpg'),
(682, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-10.jpg'),
(683, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-11.jpg'),
(684, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-12.jpg'),
(685, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-13.jpg'),
(686, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-14.jpg'),
(687, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-15.jpg'),
(688, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-16.jpg'),
(689, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-17.jpg'),
(690, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-18.jpg'),
(691, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-19.jpg'),
(692, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-20.jpg'),
(693, 95, '../asset/product/MB/MB-Astray Red Frame Gundam/Astray Red Frame Gundam-21.jpg'),
(694, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-2.jpg'),
(695, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-3.jpg'),
(696, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-4.jpg'),
(697, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-5.jpg'),
(698, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-6.jpg'),
(699, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-7.jpg'),
(700, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-8.jpg'),
(701, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-9.jpg'),
(702, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-10.jpg'),
(703, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-11.jpg'),
(704, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-12.jpg'),
(705, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-13.jpg'),
(706, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-14.jpg'),
(707, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-15.jpg'),
(708, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-16.jpg'),
(709, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-17.jpg'),
(710, 96, '../asset/product/MB/MB-F91 Gundam/F91 Gundam-18.jpg'),
(711, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-2.jpg'),
(712, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-3.jpg'),
(713, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-4.jpg'),
(714, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-5.jpg'),
(715, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-6.jpg'),
(716, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-7.jpg'),
(717, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-8.jpg'),
(718, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-9.jpg'),
(719, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-10.jpg'),
(720, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-11.jpg'),
(721, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-12.jpg'),
(722, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-13.jpg'),
(723, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-14.jpg'),
(724, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-15.jpg'),
(725, 97, '../asset/product/MB/MB-Freedom Gundam/Freedom Gundam-16.jpg'),
(726, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-2.jpg'),
(727, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-3.jpg'),
(728, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-4.jpg'),
(729, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-5.jpg'),
(730, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-6.jpg'),
(731, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-7.jpg'),
(732, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-8.jpg'),
(733, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-9.jpg'),
(734, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-10.jpg'),
(735, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-11.jpg'),
(736, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-12.jpg'),
(737, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-13.jpg'),
(738, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-14.jpg'),
(739, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-15.jpg'),
(740, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-16.jpg'),
(741, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-17.jpg'),
(742, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-18.jpg'),
(743, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-19.jpg'),
(744, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-20.jpg'),
(745, 98, '../asset/product/MB/MB-Hi-Nu Gundam/Hi-Nu Gundam-21.jpg'),
(746, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-2.jpg'),
(747, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-3.jpg'),
(748, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-4.jpg'),
(749, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-5.jpg'),
(750, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-6.jpg'),
(751, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-7.jpg'),
(752, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-8.jpg'),
(753, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-9.jpg'),
(754, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-10.jpg'),
(755, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-11.jpg'),
(756, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-12.jpg'),
(757, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-13.jpg'),
(758, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-14.jpg'),
(759, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-15.jpg'),
(760, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-16.jpg'),
(761, 99, '../asset/product/MB/MB-Justice Gundam/Justice Gundam-17.jpg'),
(762, 109, '../asset/product/Base/Base-Action Base 5 Black/Action Base 5 Black-2.jpg'),
(763, 109, '../asset/product/Base/Base-Action Base 5 Black/Action Base 5 Black-3.jpg'),
(764, 109, '../asset/product/Base/Base-Action Base 5 Black/Action Base 5 Black-4.jpg'),
(765, 109, '../asset/product/Base/Base-Action Base 5 Black/Action Base 5 Black-5.jpg'),
(766, 110, '../asset/product/Base/Base-Giá đỡ mô hình Model Effect/Giá đỡ mô hình Model Effect-2.png'),
(767, 110, '../asset/product/Base/Base-Giá đỡ mô hình Model Effect/Giá đỡ mô hình Model Effect-3.png'),
(768, 110, '../asset/product/Base/Base-Giá đỡ mô hình Model Effect/Giá đỡ mô hình Model Effect-4.png'),
(769, 111, '../asset/product/Base/Base-Gundam Mechanical Base/Gundam Mechanical Base-2.jpg'),
(770, 112, '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-2.jpg'),
(771, 112, '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-3.jpg'),
(772, 112, '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-4.jpg'),
(773, 112, '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-5.jpg'),
(774, 112, '../asset/product/Base/Base-Hộp trưng bày Gundam có Led/Hộp trưng bày Gundam có Led-6.jpg'),
(785, 113, '../asset/product/MG/MG-Banshee Gundam/Banshee Gundam-1.jpg');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `product_detail`
--
ALTER TABLE `product_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_img`
--
ALTER TABLE `product_img`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=786;

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
