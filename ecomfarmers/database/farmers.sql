-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2023 at 04:58 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
(10, 'May Borigas2', 'may2@gmail.com', '09219655318', 'test', 'Libon', 'May2', 'dc647eb65e6711e155375218212b3964', '2023-09-28 22:49:37', 'User');

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
(3, 'Admin', 'admin@gmail.com', '09219655318', '', 'dwadkla', 'Credit Card', '', '2023-08-16 03:50:12'),
(4, 'Juvelyn Arjona', 'juvelyn@gmail.com', '09219655318', '', 'test', 'COD', '', '2023-08-29 12:29:09'),
(5, 'Juvelyn2', 'juvelyn@gmail.com', '09219655318', '', 'test', 'COD', '', '2023-08-29 12:39:40'),
(6, 'Dhine Arjona', 'dhine@gmail.com', '09219655318', 'test', 'Polangui', 'Pick-Up', '', '2023-09-19 03:04:23'),
(7, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'Pick-Up', '', '2023-09-27 21:23:38'),
(8, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'Pick-Up', '', '2023-09-27 21:23:57'),
(9, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'Pick-Up', '', '2023-09-27 21:24:16'),
(10, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'Pick-Up', '6514ad97d630c', '2023-09-27 22:32:55'),
(11, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'Pick-Up', '6515462faf815', '2023-09-28 09:23:59'),
(12, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'COD', '6515463bac1d7', '2023-09-28 09:24:11'),
(13, 'James Satiada', 'james@gmail.com', '09219655318', 'test', 'Polangui', 'Gcash', '651546446049b', '2023-09-28 09:24:20'),
(14, 'James Satiada2', 'james2@gmail.com', '09219655318', 'test', 'Libon', 'Pick-Up', '651548784870a', '2023-09-28 09:33:44'),
(15, 'James Satiada2', 'james2@gmail.com', '09219655318', 'test', 'Libon', 'Pick-Up', '651548dee16b3', '2023-09-28 09:35:26'),
(16, 'May Borigas2', 'may2@gmail.com', '09219655318', 'test', 'Libon', 'Pick-Up', '651593349f095', '2023-09-28 14:52:36');

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
(5, 4, 'Fresh Vegetables', 1, '65.00', 'Delivered'),
(6, 5, 'Fresh Vegetables', 1, '65.00', 'Ordered'),
(7, 6, 'Carpenter', 1, '500.00', 'Ordered'),
(8, 7, '', 1, '65.00', 'Ordered'),
(9, 8, '', 1, '65.00', 'Ordered'),
(10, 9, 'Fresh Vegetables', 1, '65.00', 'Ordered'),
(11, 10, 'Fresh Vegetables', 1, '65.00', 'Ordered'),
(12, 11, 'Fresh Vegetables', 1, '65.00', 'Ordered'),
(13, 12, 'Fresh Vegetables', 1, '65.00', 'Ordered'),
(14, 13, 'Fresh Vegetables', 1, '65.00', 'Ordered'),
(15, 14, 'Carpenter', 1, '500.00', 'Ordered'),
(16, 15, 'Fresh Vegetables', 7, '455.00', 'Ordered'),
(17, 15, 'Fresh Vegetables', 10, '650.00', 'Ordered'),
(18, 16, 'Fresh Vegetables', 3, '195.00', 'Ordered');

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
  `harvest` date NOT NULL DEFAULT current_timestamp(),
  `timestamp` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `image`, `title`, `description`, `price`, `quantity`, `categories`, `harvest`, `timestamp`) VALUES
(1, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 200, 'Vegetables', '2023-09-18', '2023-08-21 09:45:46'),
(2, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 200, 'Vegetables', '2023-09-18', '2023-08-21 09:45:51'),
(3, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 200, 'Fish', '2023-09-18', '2023-08-21 09:45:55'),
(4, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 200, 'Fish', '2023-09-18', '2023-08-21 09:45:58'),
(5, 'seeds.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 65, 200, 'Meats', '2023-09-18', '2023-08-21 09:46:03'),
(6, 'default.jpg', 'Fresh Vegetables', 'Healthy and delicious vegetables for your meals.', 700, 2000, 'Rice', '2023-09-18', '2023-08-30 20:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `timestamp` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `image`, `title`, `description`, `price`, `quantity`, `categories`, `timestamp`) VALUES
(1, 'carpenter.png', 'Carpenter', 'Carpenter', 500, 200, 'Carpenter', '2023-09-19'),
(2, 'plumber.jpg', 'Plumber', 'Plumber', 500, 200, 'Plumber', '2023-09-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
