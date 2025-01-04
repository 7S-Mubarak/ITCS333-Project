-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2024 at 01:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supermarketdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(6) UNSIGNED NOT NULL,
  `userId` int(6) UNSIGNED DEFAULT NULL,
  `orderDetails` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'acknowledged',
  `totalprice` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `userId`, `orderDetails`, `status`, `totalprice`) VALUES
(2, 1, 'BANAN-qty(4)\r\ntomato-qty(7)\r\nwireless headphone', 'in process', 12),
(8, 1, 'iphone 15 pro max-qty(1)', 'acknowledged', 300),
(12, 16, 'apple - red local origin apple (Qty: 2), sony headphone - high sound quality  (Qty: 2),  -  (Qty: 0), cannon camera - high quality camera (Qty: 1), ', 'acknowledged', 417),
(13, 16, 'tomato - fresh and juicy tomato (Qty: 1), banana - local origin banana! (Qty: 3), iphone 15 pro max - latest iphone smartphone (Qty: 1), ', 'acknowledged', 350),
(14, 16, 'tomato - fresh and juicy tomato (Qty: 1), ', 'acknowledged', 1),
(15, 16, 'smart watch - black sport watch (Qty: 1), iphone 15 pro max - latest iphone smartphone (Qty: 1), ', 'acknowledged', 379),
(16, 16, 'tomato - fresh and juicy tomato (Qty: 1), carrot - Crespy carrot (Qty: 1), apple - red local origin apple (Qty: 1), ', 'acknowledged', 3),
(17, 20, 'tomato - fresh and juicy tomato (Qty: 1), iphone 15 pro max - latest iphone smartphone (Qty: 1), ', 'acknowledged', 346),
(18, 20, '', 'acknowledged', 346),
(19, 20, '', 'acknowledged', 346),
(20, 20, '', 'acknowledged', 346),
(21, 20, '', 'acknowledged', 346),
(22, 20, 'banana - local origin banana! (Qty: 1), ', 'acknowledged', 1),
(23, 20, '', 'acknowledged', 1),
(24, 20, '', 'acknowledged', 1),
(25, 20, '', 'acknowledged', 1),
(26, 20, '', 'acknowledged', 1),
(27, 20, '', 'acknowledged', 1),
(28, 20, '', 'acknowledged', 1),
(29, 20, '', 'acknowledged', 1),
(30, 20, '', 'acknowledged', NULL),
(31, 20, '', 'acknowledged', 5),
(32, 20, '', 'acknowledged', 5),
(33, 20, '', 'acknowledged', 5),
(34, 20, '', 'acknowledged', 5),
(35, 20, '', 'acknowledged', 5),
(36, 20, '', 'acknowledged', 5),
(37, 20, '', 'acknowledged', 5),
(38, 20, '', 'acknowledged', 5),
(39, 20, '', 'acknowledged', 5),
(40, 20, '', 'acknowledged', NULL),
(41, NULL, '', 'acknowledged', NULL),
(42, 20, 'tomato - fresh and juicy tomato (Qty: 1), ', 'acknowledged', 1),
(43, 20, 'tomato - fresh and juicy tomato (Qty: 1), apple - red local origin apple (Qty: 1), ', 'acknowledged', 2),
(44, 20, '', 'acknowledged', 2),
(45, 20, '', 'acknowledged', 2),
(46, 21, '', 'acknowledged', 2),
(47, 21, '', 'acknowledged', 2),
(48, 21, '', 'acknowledged', 2),
(49, 21, '', 'acknowledged', 2),
(50, 21, '', 'acknowledged', 2),
(51, 20, '', 'acknowledged', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productId` int(6) UNSIGNED NOT NULL,
  `productname` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `pic` varchar(20) DEFAULT NULL,
  `qty` int(6) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `productDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productId`, `productname`, `category`, `details`, `pic`, `qty`, `price`, `productDate`) VALUES
(1, 'banana', 'fruits&vegetables', 'local origin banana!', 'p3.jpg', 120, 1.25, '2024-05-16 15:51:30'),
(2, 'tomato', 'fruits&vegetables', 'fresh and juicy tomato', 'p2.jpg', 50, 0.77, '2024-05-16 17:23:12'),
(3, 'apple', 'fruits&vegetables', 'red local origin apple', 'p4.jpg', 60, 0.65, '2024-05-16 17:24:01'),
(4, 'carrot', 'fruits&vegetables', 'Fresh fruit from Everland Switzerland', 'p1.jpg', 4, 0.20, '2024-05-16 17:26:10'),
(5, 'sony headphone', 'Electronics', 'high sound quality ', 'p5.jpg', 20, 14.99, '2024-05-16 17:27:40'),
(6, 'cannon camera', 'Electronics', 'high quality camera', 'p6.jpg', 10, 330.99, '2024-05-16 17:28:30'),
(7, 'iphone 15 pro max', 'Electronics', 'latest iphone smartphone', 'p7.jpg', 70, 299.99, '2024-05-16 17:29:40'),
(8, 'smart watch', 'Electronics', 'black sport watch', 'p8.jpg', 13, 29.99, '2024-05-16 17:30:51'),
(9, 'linda', 'beverages', 'fresh', 'linda.jpg', 30, 0.25, '2024-05-16 19:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `role`) VALUES
(1, 'arfalqtan@gmail.com', 'aref abbas', '123sar', 'customer'),
(3, 'dsbhfs@gmail.com', 'dfdsfbnj', '1234df', 'customer'),
(15, 'admin@gmail.com', 'admin', 'abc123', 'admin'),
(16, 'moh@gmail.com', 'mohammed', '123abc', 'customer'),
(20, 'qwerty@gmail.com', 'sdg', 'qwerty123', 'customer'),
(21, 'test@gmail.com', 'jhdgf', 'lol123', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productId` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
