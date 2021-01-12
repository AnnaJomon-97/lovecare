-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2020 at 07:00 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lovecare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `email` varchar(125) NOT NULL,
  `username` varchar(125) NOT NULL,
  `password` varchar(125) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `username`, `password`, `status`) VALUES
(1, 'Admin', 'admin@lovecare.com', 'admin@lovecare.com', '1234', 1);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `description` text NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `logo`) VALUES
(1, 'Sun Pharmaceutical', '', 'uploads/brands/sunfarma.jpg'),
(2, 'Aurobindo Pharma Limited', '', 'uploads/brands/aurobindo.jpg'),
(3, 'Lupin Limited ', '', 'uploads/brands/lupin.jpg'),
(4, 'Cipla Limited', '', 'uploads/brands/cipla.jpg'),
(5, 'Cadila Healthcare Limited ', '', 'uploads/brands/cadila.jpg'),
(6, 'Intas Pharmaceuticals', '', 'uploads/brands/intas.jpg'),
(7, 'Glenmark Pharma Limited', '', 'uploads/brands/gelmark.jpg'),
(8, 'ManKind Pharma Limited', '', 'uploads/brands/mankindPharma.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `reg_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `status`, `reg_date`) VALUES
(1, 'Santhosh M.S', 'santhoshms01@gmail.com', '1234', 1, '2020-11-21');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `assign_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `password` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `disc_type` varchar(55) NOT NULL,
  `disc_percentage` float NOT NULL,
  `disc_amount` float NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `subcategory_id`, `brand_id`, `name`, `description`, `image`, `price`, `disc_type`, `disc_percentage`, `disc_amount`, `stock`) VALUES
(1, 1, 1, 'Mask1', 'Mask1- Description', 'uploads/items/mask.jpg', 100, 'PERCENTAGE', 0, 0, 0),
(2, 1, 2, 'Mask2', 'Mask2- Description', '', 200, '', 0, 0, 0),
(3, 1, 3, 'Mask3', 'Mask3- Description', '', 300, '', 0, 0, 0),
(4, 1, 4, 'Mask4', 'Mask4- Description', '', 400, '', 0, 0, 0),
(5, 1, 5, 'Mask5', 'Mask5- Description', '', 500, '', 0, 0, 0),
(6, 1, 6, 'Mask6', 'Mask6- Description', '', 600, '', 0, 0, 0),
(7, 1, 7, 'Mask7', 'Mask7- Description', '', 700, '', 0, 0, 0),
(8, 1, 8, 'Mask8', 'Mask8- Description', '', 800, '', 0, 0, 0),
(9, 2, 1, 'Sanitizer1', 'Sanitizer1- Description', '', 100, '', 0, 0, 0),
(10, 2, 2, 'Sanitizer2', 'Sanitizer2- Description', '', 200, '', 0, 0, 0),
(11, 2, 3, 'Sanitizer3', 'Sanitizer3- Description', '', 300, '', 0, 0, 0),
(12, 2, 4, 'Sanitizer4', 'Sanitizer4- Description', '', 400, '', 0, 0, 0),
(13, 2, 5, 'Sanitizer5', 'Sanitizer5- Description', '', 500, '', 0, 0, 0),
(14, 2, 6, 'Sanitizer6', 'Sanitizer6- Description', '', 600, '', 0, 0, 0),
(15, 2, 7, 'Sanitizer7', 'Sanitizer7- Description', '', 700, '', 0, 0, 0),
(16, 2, 8, 'Sanitizer8', 'Sanitizer8- Description', '', 800, '', 0, 0, 0),
(17, 3, 1, 'Band-aid1', 'Band-aid1- Description', '', 100, '', 0, 0, 0),
(18, 3, 2, 'Band-aid2', 'Band-aid2- Description', '', 200, '', 0, 0, 0),
(19, 3, 3, 'Band-aid3', 'Band-aid3- Description', '', 300, '', 0, 0, 0),
(20, 3, 4, 'Band-aid4', 'Band-aid4- Description', '', 400, '', 0, 0, 0),
(21, 3, 5, 'Band-aid5', 'Band-aid5- Description', '', 500, '', 0, 0, 0),
(22, 3, 6, 'Band-aid6', 'Band-aid6- Description', '', 600, '', 0, 0, 0),
(23, 3, 7, 'Band-aid7', 'Band-aid7- Description', '', 700, '', 0, 0, 0),
(24, 3, 8, 'Band-aid8', 'Band-aid8- Description', '', 800, '', 0, 0, 0),
(25, 4, 1, 'Soaps &amp; Body wash1', 'Soaps &amp; Body wash1- Description', '', 100, '', 0, 0, 0),
(26, 4, 2, 'Soaps &amp; Body wash2', 'Soaps &amp; Body wash2- Description', '', 200, '', 0, 0, 0),
(27, 4, 3, 'Soaps &amp; Body wash3', 'Soaps &amp; Body wash3- Description', '', 300, '', 0, 0, 0),
(28, 4, 4, 'Soaps &amp; Body wash4', 'Soaps &amp; Body wash4- Description', '', 400, '', 0, 0, 0),
(29, 4, 5, 'Soaps &amp; Body wash5', 'Soaps &amp; Body wash5- Description', '', 500, '', 0, 0, 0),
(30, 4, 6, 'Soaps &amp; Body wash6', 'Soaps &amp; Body wash6- Description', '', 600, '', 0, 0, 0),
(31, 4, 7, 'Soaps &amp; Body wash7', 'Soaps &amp; Body wash7- Description', '', 700, '', 0, 0, 0),
(32, 4, 8, 'Soaps &amp; Body wash8', 'Soaps &amp; Body wash8- Description', '', 800, '', 0, 0, 0),
(33, 5, 1, 'Crutches1', 'Crutches1- Description', '', 100, '', 0, 0, 0),
(34, 5, 2, 'Crutches2', 'Crutches2- Description', '', 200, '', 0, 0, 0),
(35, 5, 3, 'Crutches3', 'Crutches3- Description', '', 300, '', 0, 0, 0),
(36, 5, 4, 'Crutches4', 'Crutches4- Description', '', 400, '', 0, 0, 0),
(37, 5, 5, 'Crutches5', 'Crutches5- Description', '', 500, '', 0, 0, 0),
(38, 5, 6, 'Crutches6', 'Crutches6- Description', '', 600, '', 0, 0, 0),
(39, 5, 7, 'Crutches7', 'Crutches7- Description', '', 700, '', 0, 0, 0),
(40, 5, 8, 'Crutches8', 'Crutches8- Description', '', 800, '', 0, 0, 0),
(41, 6, 1, 'Test Devices1', 'Test Devices1- Description', '', 100, '', 0, 0, 0),
(42, 6, 2, 'Test Devices2', 'Test Devices2- Description', '', 200, '', 0, 0, 0),
(43, 6, 3, 'Test Devices3', 'Test Devices3- Description', '', 300, '', 0, 0, 0),
(44, 6, 4, 'Test Devices4', 'Test Devices4- Description', '', 400, '', 0, 0, 0),
(45, 6, 5, 'Test Devices5', 'Test Devices5- Description', '', 500, '', 0, 0, 0),
(46, 6, 6, 'Test Devices6', 'Test Devices6- Description', '', 600, '', 0, 0, 0),
(47, 6, 7, 'Test Devices7', 'Test Devices7- Description', '', 700, '', 0, 0, 0),
(48, 6, 8, 'Test Devices8', 'Test Devices8- Description', '', 800, '', 0, 0, 0),
(49, 7, 1, 'Tablets1', 'Tablets1- Description', '', 100, '', 0, 0, 0),
(50, 7, 2, 'Tablets2', 'Tablets2- Description', '', 200, '', 0, 0, 0),
(51, 7, 3, 'Tablets3', 'Tablets3- Description', '', 300, '', 0, 0, 0),
(52, 7, 4, 'Tablets4', 'Tablets4- Description', '', 400, '', 0, 0, 0),
(53, 7, 5, 'Tablets5', 'Tablets5- Description', '', 500, '', 0, 0, 0),
(54, 7, 6, 'Tablets6', 'Tablets6- Description', '', 600, '', 0, 0, 0),
(55, 7, 7, 'Tablets7', 'Tablets7- Description', '', 700, '', 0, 0, 0),
(56, 7, 8, 'Tablets8', 'Tablets8- Description', '', 800, '', 0, 0, 0),
(57, 8, 1, 'Syrup1', 'Syrup1- Description', '', 100, '', 0, 0, 0),
(58, 8, 2, 'Syrup2', 'Syrup2- Description', '', 200, '', 0, 0, 0),
(59, 8, 3, 'Syrup3', 'Syrup3- Description', '', 300, '', 0, 0, 0),
(60, 8, 4, 'Syrup4', 'Syrup4- Description', '', 400, '', 0, 0, 0),
(61, 8, 5, 'Syrup5', 'Syrup5- Description', '', 500, '', 0, 0, 0),
(62, 8, 6, 'Syrup6', 'Syrup6- Description', '', 600, '', 0, 0, 0),
(63, 8, 7, 'Syrup7', 'Syrup7- Description', '', 700, '', 0, 0, 0),
(64, 8, 8, 'Syrup8', 'Syrup8- Description', '', 800, '', 0, 0, 0),
(65, 9, 1, 'Capsules1', 'Capsules1- Description', '', 100, '', 0, 0, 0),
(66, 9, 2, 'Capsules2', 'Capsules2- Description', '', 200, '', 0, 0, 0),
(67, 9, 3, 'Capsules3', 'Capsules3- Description', '', 300, '', 0, 0, 0),
(68, 9, 4, 'Capsules4', 'Capsules4- Description', '', 400, '', 0, 0, 0),
(69, 9, 5, 'Capsules5', 'Capsules5- Description', '', 500, '', 0, 0, 0),
(70, 9, 6, 'Capsules6', 'Capsules6- Description', '', 600, '', 0, 0, 0),
(71, 9, 7, 'Capsules7', 'Capsules7- Description', '', 700, '', 0, 0, 0),
(72, 9, 8, 'Capsules8', 'Capsules8- Description', '', 800, '', 0, 0, 0),
(73, 10, 1, 'Creams&amp;Ointment1', 'Creams&amp;Ointment1- Description', '', 100, '', 0, 0, 0),
(74, 10, 2, 'Creams&amp;Ointment2', 'Creams&amp;Ointment2- Description', '', 200, '', 0, 0, 0),
(75, 10, 3, 'Creams&amp;Ointment3', 'Creams&amp;Ointment3- Description', '', 300, '', 0, 0, 0),
(76, 10, 4, 'Creams&amp;Ointment4', 'Creams&amp;Ointment4- Description', '', 400, '', 0, 0, 0),
(77, 10, 5, 'Creams&amp;Ointment5', 'Creams&amp;Ointment5- Description', '', 500, '', 0, 0, 0),
(78, 10, 6, 'Creams&amp;Ointment6', 'Creams&amp;Ointment6- Description', '', 600, '', 0, 0, 0),
(79, 10, 7, 'Creams&amp;Ointment7', 'Creams&amp;Ointment7- Description', '', 700, '', 0, 0, 0),
(80, 10, 8, 'Creams&amp;Ointment8', 'Creams&amp;Ointment8- Description', '', 800, '', 0, 0, 0),
(81, 11, 1, 'Vegitables1', 'Vegitables1- Description', '', 100, '', 0, 0, 0),
(82, 11, 2, 'Vegitables2', 'Vegitables2- Description', '', 200, '', 0, 0, 0),
(83, 11, 3, 'Vegitables3', 'Vegitables3- Description', '', 300, '', 0, 0, 0),
(84, 11, 4, 'Vegitables4', 'Vegitables4- Description', '', 400, '', 0, 0, 0),
(85, 11, 5, 'Vegitables5', 'Vegitables5- Description', '', 500, '', 0, 0, 0),
(86, 11, 6, 'Vegitables6', 'Vegitables6- Description', '', 600, '', 0, 0, 0),
(87, 11, 7, 'Vegitables7', 'Vegitables7- Description', '', 700, '', 0, 0, 0),
(88, 11, 8, 'Vegitables8', 'Vegitables8- Description', '', 800, '', 0, 0, 0),
(89, 12, 1, 'Fruits1', 'Fruits1- Description', '', 100, '', 0, 0, 0),
(90, 12, 2, 'Fruits2', 'Fruits2- Description', '', 200, '', 0, 0, 0),
(91, 12, 3, 'Fruits3', 'Fruits3- Description', '', 300, '', 0, 0, 0),
(92, 12, 4, 'Fruits4', 'Fruits4- Description', '', 400, '', 0, 0, 0),
(93, 12, 5, 'Fruits5', 'Fruits5- Description', '', 500, '', 0, 0, 0),
(94, 12, 6, 'Fruits6', 'Fruits6- Description', '', 600, '', 0, 0, 0),
(95, 12, 7, 'Fruits7', 'Fruits7- Description', '', 700, '', 0, 0, 0),
(96, 12, 8, 'Fruits8', 'Fruits8- Description', '', 800, '', 0, 0, 0),
(97, 13, 1, ' Cereals1', ' Cereals1- Description', '', 100, '', 0, 0, 0),
(98, 13, 2, ' Cereals2', ' Cereals2- Description', '', 200, '', 0, 0, 0),
(99, 13, 3, ' Cereals3', ' Cereals3- Description', '', 300, '', 0, 0, 0),
(100, 13, 4, ' Cereals4', ' Cereals4- Description', '', 400, '', 0, 0, 0),
(101, 13, 5, ' Cereals5', ' Cereals5- Description', '', 500, '', 0, 0, 0),
(102, 13, 6, ' Cereals6', ' Cereals6- Description', '', 600, '', 0, 0, 0),
(103, 13, 7, ' Cereals7', ' Cereals7- Description', '', 700, '', 0, 0, 0),
(104, 13, 8, ' Cereals8', ' Cereals8- Description', '', 800, '', 0, 0, 0),
(105, 14, 1, 'Ingredients1', 'Ingredients1- Description', '', 100, '', 0, 0, 0),
(106, 14, 2, 'Ingredients2', 'Ingredients2- Description', '', 200, '', 0, 0, 0),
(107, 14, 3, 'Ingredients3', 'Ingredients3- Description', '', 300, '', 0, 0, 0),
(108, 14, 4, 'Ingredients4', 'Ingredients4- Description', '', 400, '', 0, 0, 0),
(109, 14, 5, 'Ingredients5', 'Ingredients5- Description', '', 500, '', 0, 0, 0),
(110, 14, 6, 'Ingredients6', 'Ingredients6- Description', '', 600, '', 0, 0, 0),
(111, 14, 7, 'Ingredients7', 'Ingredients7- Description', '', 700, '', 0, 0, 0),
(112, 14, 8, 'Ingredients8', 'Ingredients8- Description', '', 800, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `name`, `description`, `image`) VALUES
(1, 'Utilities', 'Physical Aids, First aid etc\r\n', 'uploads/categories/equipnts.jpg'),
(2, 'Equipments', 'Cruches', 'uploads/categories/equipments.jpg'),
(3, 'Medicins', '', 'uploads/categories/medicins.jpg'),
(4, 'Groceries', '', 'uploads/categories/grocery.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `item_subcategories`
--

CREATE TABLE `item_subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_subcategories`
--

INSERT INTO `item_subcategories` (`id`, `category_id`, `name`, `description`, `image`) VALUES
(1, 1, 'Mask', '', 'uploads/subcategories/mask.jpg'),
(2, 1, 'Sanitizer', '', 'uploads/subcategories/sanitiser.jpg'),
(3, 1, 'Band-aid', '', 'uploads/subcategories/band-aid_1.jpg'),
(4, 1, 'Soaps &amp; Body wash', '', 'uploads/subcategories/sopas.jpg'),
(5, 2, 'Crutches', '', 'uploads/subcategories/crutches.jpg'),
(6, 2, 'Test Devices', '', 'uploads/subcategories/test_devices.jpg'),
(7, 3, 'Tablets', '', ''),
(8, 3, 'Syrup', '', ''),
(9, 3, 'Capsules', '', ''),
(10, 3, 'Creams&amp;Ointment', '', ''),
(11, 4, 'Vegitables', '', ''),
(12, 4, 'Fruits', '', ''),
(13, 4, ' Cereals', '', ''),
(14, 4, 'Ingredients', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `phone` varchar(55) NOT NULL,
  `subject` varchar(125) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(55) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(4) NOT NULL,
  `cancelled` tinyint(4) DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `payment_type` varchar(55) DEFAULT NULL,
  `payment_status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `name`, `address`, `phone`, `date`, `status`, `cancelled`, `cancellation_reason`, `payment_type`, `payment_status`) VALUES
(1, 1, 'Vimal Kumar', 'Maliyekkal', '9745399695', '2020-11-25', 1, 0, '', 'CASH_ON_DELIVERY', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `quantity`, `price`) VALUES
(1, 1, 1, 6, 100),
(2, 1, 81, 5, 100);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `purchase_rate` double NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `review` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `fee` double NOT NULL,
  `unit` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `image`, `fee`, `unit`) VALUES
(1, 'Home Nurse Service', 'Caring people offers Best Home Nursing Services, Get Free Expert Assessment. 24/7 Patient Monitoring, Feeding, Bathing &amp; Medication at the Comfort of Home', 'uploads/services/home-nurse-service.jpg', 500, 'Day'),
(2, 'Home Physiotherapy Services', 'Physical therapy, also known as physiotherapy, is one of the allied health professions that, by using evidence-based kinesiology, exercise prescription, health education, mobilizationetc', 'uploads/services/physiotherapy.jpg', 600, 'Day');

-- --------------------------------------------------------

--
-- Table structure for table `service_booking`
--

CREATE TABLE `service_booking` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `name` varchar(55) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `cancelled` tinyint(4) NOT NULL DEFAULT 0,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_booking`
--

INSERT INTO `service_booking` (`id`, `customer_id`, `service_id`, `date_from`, `date_to`, `name`, `address`, `phone`, `status`, `cancelled`, `cancellation_reason`, `created_at`) VALUES
(1, 1, 1, '2020-11-26', '2020-11-28', 'Anil Kumar', 'Palakkal', '9947709906', 1, 0, NULL, '2020-11-26 05:59:51');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL,
  `phone` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_subcategories`
--
ALTER TABLE `item_subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_booking`
--
ALTER TABLE `service_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_subcategories`
--
ALTER TABLE `item_subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_booking`
--
ALTER TABLE `service_booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
