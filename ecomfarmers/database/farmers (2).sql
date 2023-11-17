-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2023 at 07:11 AM
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
-- Database: `farmers`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usertype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `fullname`, `email`, `contact`, `address`, `location`, `username`, `password`, `timestamp`, `usertype`) VALUES
(2, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'James1', 'dc647eb65e6711e155375218212b3964', '2023-09-28 05:19:38', 'User'),
(4, 'May Borigas', '', '0', '', '', 'May1', 'dc647eb65e6711e155375218212b3964', '2023-08-20 18:37:05', 'Admin'),
(8, 'James Satiada2', 'james2@gmail.com', '09219655318', 'test', 'Libon', 'James2', 'dc647eb65e6711e155375218212b3964', '2023-09-28 17:33:18', 'User'),
(9, 'Dhine Arjona', 'dhine@gmail.com', '09219655318', 'test', 'test', 'Dhine16', 'dc647eb65e6711e155375218212b3964', '2023-09-28 20:36:56', 'Admin'),
(10, 'May Borigas2', 'may2@gmail.com', '09219655318', 'test', 'Libon', 'May2', 'dc647eb65e6711e155375218212b3964', '2023-09-28 22:49:37', 'User'),
(11, 'Admin', 'admin@gmail.com', '09457792466', 'Balogo, Oas, Albay', 'Oas', 'admin1', '21232f297a57a5a743894a0e4a801fc3', '2023-11-09 17:44:47', 'Admin'),
(12, 'user', 'user@gmail.com', '09478234567', 'Centro, Libon, Albay', 'Libon', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', '2023-11-10 08:49:08', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `availed_service`
--

CREATE TABLE `availed_service` (
  `avail_id` int(20) NOT NULL,
  `service_avail_ref` varchar(340) NOT NULL,
  `service_id` int(200) NOT NULL,
  `date` date DEFAULT NULL,
  `avail_status` text NOT NULL,
  `account_id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availed_service`
--

INSERT INTO `availed_service` (`avail_id`, `service_avail_ref`, `service_id`, `date`, `avail_status`, `account_id`) VALUES
(16, 'REF_655329dfed71a', 4, '2023-11-16', 'Completed', 12),
(17, 'REF_65532a93d2531', 3, '2023-11-16', 'Ordered', 12),
(18, 'REF_65532ac296bb2', 4, '2023-11-17', 'Ordered', 12);

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `ID` int(11) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Contact` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `Location` text NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `Order_ID` varchar(255) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`ID`, `Fullname`, `Email`, `Contact`, `address`, `Location`, `PaymentMethod`, `Order_ID`, `Timestamp`) VALUES
(29, 'user', 'user@gmail.com', '09478234567', 'Centro, Libon, Albay', 'Libon', 'Pick-Up', 'SAUD-000002', '2023-11-17 03:16:04'),
(30, 'user', 'user@gmail.com', '09478234567', 'Centro, Libon, Albay', 'Libon', 'Pick-Up', 'SAUD-000003', '2023-11-17 03:16:26');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(40) NOT NULL,
  `cat_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(1, 'Vegetables'),
(2, 'Fish'),
(3, 'Meats'),
(4, 'Rice'),
(5, 'Fruit'),
(8, 'Animal Product');

-- --------------------------------------------------------

--
-- Table structure for table `preorder`
--

CREATE TABLE `preorder` (
  `id` int(100) NOT NULL,
  `account_id` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `quantity_pre` bigint(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `price` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preorder`
--

INSERT INTO `preorder` (`id`, `account_id`, `title`, `quantity_pre`, `product_id`, `price`) VALUES
(4, 12, 'Kalabasa', 1, 14, 30),
(5, 12, 'Bawang', 3, 15, 12);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `BillingID` int(11) DEFAULT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `Status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `BillingID`, `ProductName`, `Quantity`, `Total`, `Status`) VALUES
(31, 29, 'Fresh Vegetables', 1, 65.00, 'Ordered'),
(32, 29, 'Kalabasa', 1, 30.00, 'Ordered'),
(33, 30, 'Fresh Vegetables', 1, 70.00, 'Ordered'),
(34, 30, 'Kalabasa', 1, 30.00, 'Ordered');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(11) NOT NULL,
  `image` varchar(300) NOT NULL,
  `title` varchar(300) NOT NULL,
  `description` varchar(300) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `harvest` date DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unit` text NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `image`, `title`, `description`, `price`, `quantity`, `categories`, `harvest`, `timestamp`, `unit`, `status`) VALUES
(1, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 194, 'Fish', '0000-00-00', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(2, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 194, 'Vegetables', '2023-09-18', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(3, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 194, 'Fish', '0000-00-00', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(4, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 194, 'Fish', '2023-09-18', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(5, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 194, 'Meats', '2023-09-18', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(6, 'default.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 70, 1994, 'Rice', '0000-00-00', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(14, 'kalabasa.jpg', 'Kalabasa', 'No pesticide used.', 30, 17, 'Vegetables', '2023-11-16', '2023-11-17 11:16:26', 'Kilo', 'On Sale'),
(15, '1200px-Bawang.jpg', 'Bawang', 'Bawang', 12, 200, 'Vegetables', '2023-11-17', '2023-11-14 13:45:28', 'Kilo', 'Pre Order'),
(18, 'bunay.jpg', 'Bunay', 'Eggs are classified as an animal product, as opposed to fruits and vegetables, which are plant products. ', 10, 1000, 'Animal Product', NULL, '2023-11-17 13:45:04', 'Piece', 'On Sale');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `service_price_range` varchar(250) NOT NULL,
  `timestamp` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `image`, `title`, `description`, `service_price_range`, `timestamp`) VALUES
(3, 'tubero.jpg', 'Tubero', 'Unclogging, Cleaning, Repairing\r\n', '1000-1500', '2023-11-13'),
(4, '395488144_727808762501215_4830566718481932710_n.jpg', 'Selfie', 'Mcdo, Night Ride', '500', '2023-11-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `availed_service`
--
ALTER TABLE `availed_service`
  ADD PRIMARY KEY (`avail_id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `preorder`
--
ALTER TABLE `preorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `BillingID` (`BillingID`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `availed_service`
--
ALTER TABLE `availed_service`
  MODIFY `avail_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `preorder`
--
ALTER TABLE `preorder`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`BillingID`) REFERENCES `billing` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
