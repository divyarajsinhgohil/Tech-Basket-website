-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 10:06 AM
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
-- Database: `project_2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(5) NOT NULL,
  `catname` varchar(100) NOT NULL,
  `catdescription` text NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `catname`, `catdescription`, `image`) VALUES
(1, 'Desktops', '', '1757136677_download (3).jpg'),
(2, 'Laptops', '', '1757136701_download (10).jpg'),
(3, 'Computer Components', '', '1757228844_computer-parts-decorative-icons-set_1284-5170.jpg'),
(5, 'Gaming & Accessories', '', '1757228925_images (1).jpg'),
(6, 'Software', '', '1757228966_images (2).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_user`
--

CREATE TABLE `front_user` (
  `id` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` varchar(250) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `username`, `email`, `mobile_number`, `password`, `created_at`) VALUES
(1, 'aaa', 'aaa@gmail.com', '0123456789', '$2y$10$tqmECfDH3An2j2TMz2e6XuQKIst1QTY.Ki5oPzQszIqRcWgxWAim2', '2025-09-06 05:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `product_id` int(5) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_method` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(255) DEFAULT NULL,
  `order_status` varchar(100) NOT NULL DEFAULT 'Pending',
  `image` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `order_total`, `product_id`, `sub_total`, `discount`, `tax`, `shipping_cost`, `shipping_method`, `created_at`, `payment_method`, `order_status`, `image`, `product_name`, `product_description`) VALUES
(1, 'divyarajgohil', 'avv@gmail.com', '07984297377', 'gondal', 12296.00, 0, 12344.00, 40.00, 2.00, 0.00, 'Standard Delivery', '2025-07-19 05:17:39', 'credit_card', 'deleted', NULL, '', ''),
(2, 'divyarajgohil', 'cccc@gmail.com', '07984297377', 'gondal', 12296.00, 0, 12344.00, 40.00, 2.00, 0.00, 'Standard Delivery', '2025-07-25 06:00:05', 'credit_card', 'deleted', NULL, '', ''),
(3, 'divyarajgohil', 'aaa@gmail.com', '07984297377', 'gondal', 99952.00, 0, 100000.00, 40.00, 2.00, 0.00, 'Standard Delivery', '2025-08-18 03:01:46', 'cash_on_delivery', 'deleted', NULL, '', ''),
(4, 'divyarajgohil', 'aaaa@gmail.com', '07984297377', 'gondal', 99952.00, 0, 100000.00, 40.00, 2.00, 0.00, 'Standard Delivery', '2025-08-26 04:58:22', 'cash_on_delivery', 'deleted', NULL, '', ''),
(5, 'Divyaraj gohil', 'tgjhntrdfht@gmail.com', '07984297377', 'Rajkot', 9952.00, 0, 10000.00, 40.00, 2.00, 0.00, 'Standard Delivery', '2025-09-06 04:55:07', 'cash_on_delivery', 'deleted', NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(5) NOT NULL,
  `catid` int(5) NOT NULL,
  `subcatid` int(5) NOT NULL,
  `productname` varchar(100) NOT NULL,
  `productdescription` text NOT NULL,
  `productprice` int(10) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `catid`, `subcatid`, `productname`, `productdescription`, `productprice`, `image`, `image2`, `image3`, `last_viewed`) VALUES
(13, 6, 6, 'Windows 10', 'MICROSOFT Windows 10 Home (1 PC, Lifetime Validity) Professional 64/32 bit\r\n\r\nApart from visual changes, there are a number of new features in the Windows 10 Home operating system: Cortana: Personal assistant for the operation of the computer Microsoft Edge: New internet browser, replaces the existing Internet Explorer Face and fingerprint recognition (if the appropriate equipment is available) Continuum: Tablet mode for touch devices Windows Holographic: Application platform for the associated HoloLens data glasses (augmented reality)', 3000, '1757230150_windows-10-home-windows-10-home-1-pc-lifetime-validity-microsoft-original-imahekqgbfptmwds.jpeg', '', '', NULL),
(14, 6, 6, 'MICROSOFT Windows 11 Professional ', 'MICROSOFT Windows 11 Professional Updated Latest Edition (1 User 1 Pc/Laptop) Lifetime License', 1400, '1757230273_windows-11-professional-windows-11-professional-microsoft-original-imahfmqzwpmbmbbq.webp', '', '', NULL),
(15, 6, 6, 'dhcp WINDOWS 11 operating system 64bit BOOTABLE PENDRIVE', 'dhcp WINDOWS 11 operating system 64bit BOOTABLE PENDRIVE', 900, '1757230349_windows-11-dhcp-original-imahfjw6cgrh47ua.webp', '', '', NULL),
(16, 5, 23, 'Gta 5 ', '', 2000, '1757230555_-original-imahf6y77vrg7rxs.webp', '', '', NULL),
(17, 5, 23, 'cricket 24 ', '', 3999, '1757230572_pc-cricket-24-pc-no-dvd-cd-standard-edition-original-imahakezgyeqwjqe.webp', '', '', NULL),
(18, 5, 10, 'cricket 22', '', 2999, '1757230592_no-international-edition-ps4-cricket-22-international-edition-original-imag7znghntqg5tn.webp', '', '', NULL),
(19, 5, 23, 'spider man 2', '', 2500, '1757230613_no-standard-ps5-spiderman-2-standard-edn-action-ps5-original-imahc676hgzkxyev.webp', '', '', NULL),
(20, 5, 23, 'god of war remasted ', '', 5000, '1757230661_standard-edition-god-of-war-iii-remastered-ps4-original-imafqx6hrzssy5tf.webp', '', '', NULL),
(21, 5, 23, 'god of war ragnrok', 'GOD OF WAR - Ragnarok (Standard)  (for PS4)\r\n4.52,299 Ratings & 179 Reviews\r\n₹2,519₹3,99937% off\r\ni\r\n+ ₹19 Protect Promise Fee Learn more\r\nSecure delivery by 15 Sep, Monday\r\nHurry, Only 2 left!\r\nAvailable offers\r\n\r\nBank OfferGet 10% off upto ₹50 on minimum order value of ₹250T&C\r\n\r\nBank Offer5% cashback on Flipkart Axis Bank Credit Card upto ₹4,000 per statement quarterT&C\r\n\r\nBank Offer5% cashback on Flipkart SBI Credit Card upto ₹4,000 per calendar quarterT&C\r\n\r\nNo Cost EMI on Bajaj Finserv EMI CardView Plans', 1500, '1757230683_no-playstation-hits-god-of-war-full-game-ps4-original-imah8fsgqjhdkhaw.webp', '1757301222_standard-edition-god-of-war-iii-remastered-ps4-original-imafqx6hrzssy5tf.webp', '1757301222_no-standard-god-of-war-ragnar-k-full-game-ps4-original-imagk49zwt8jg8np.webp', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitesettings`
--

CREATE TABLE `sitesettings` (
  `id` int(5) NOT NULL,
  `sitename` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phoneno` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sitesettings`
--

INSERT INTO `sitesettings` (`id`, `sitename`, `address`, `phoneno`, `email`, `image`) VALUES
(1, 'shop111', ' Michael I. Days 3756 Preston Street Wichita, KS 67213', '07984297377', 'contactinfo11@gmail.com', '1753682078_logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `button_link` varchar(100) NOT NULL,
  `alignment` varchar(15) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `name`, `description`, `button_link`, `alignment`, `image`) VALUES
(1, 'Welcome', 'Your one-stop shop for the latest electronics, gadgets, and unbeatable deals. Start exploring today!', '', 'text-right', '1733985461_a95b1455-ede7-434b-aaa0-ec6cb0c9efe5.jpg'),
(2, 'Discover the latest in technology ', 'Discover the latest in technology and stay ahead of the curve with our wide selection of cutting-edg', '', 'text-center', '1733985115_b0fd18e6-5da6-4338-b67c-d5401801b70d.jpg'),
(3, 'Upgrade Your Life with Cutting-Edge Gadgets!', 'Transform your daily routine with the latest gadgets designed for convenience, innovation, and style', '', 'text-left', '1733985335_f57a8467-750b-4bd2-ad59-1571566a6834.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(5) NOT NULL,
  `catid` int(5) NOT NULL,
  `subcatname` varchar(100) NOT NULL,
  `subcatdescription` text NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `catid`, `subcatname`, `subcatdescription`, `image`) VALUES
(6, 6, 'Operating Systems', '', '1757229129_images (3).jpg'),
(7, 6, 'Security & Antivirus', '', '1757229165_antivirus-cyber-security-data-protection-260nw-1402830170.webp'),
(8, 6, 'Productivity Software', '', '1757229198_images (4).jpg'),
(10, 5, 'Gaming Accessories', '', '1757229286_BL-DEVELOPING-ACCESSORIES-1.avif'),
(11, 3, 'Processor (CPU)', '', '1757229330_images (6).jpg'),
(12, 3, 'Graphics Card (GPU)', '', '1757229373_images (7).jpg'),
(13, 3, 'Motherboards', '', '1757229403_computer-parts-close-up-chip-600nw-2539242453.webp'),
(14, 3, 'Memory (RAM)', '', '1757229440_gettyimages-92434128-612x612.jpg'),
(15, 3, 'Storage', '', '1757229478_images (8).jpg'),
(16, 3, 'Power Supplies', '', '1757229510_black-850w-power-supply-unit-with-cables-and-switch-i-o-for-atx-tower-pc-cases-isolated-o'),
(17, 1, 'Gaming Desktops', '', '1757229730_51DhQ3Ox2zL._UF1000,1000_QL80_.jpg'),
(18, 1, 'All-in-One PCs', '', '1757229757_71DuGqh+ftL._UF1000,1000_QL80_.jpg'),
(19, 2, 'Gaming Laptops', '', '1757229781_hp-original-imaftyzachgrav8f.jpeg'),
(20, 2, 'Business Laptops', '', '1757229809_w600.jpg'),
(21, 2, 'foldable laptop', '', '1757229850_images (9).jpg'),
(22, 3, 'Computer acceries ', '', '1757229929_images.jpg'),
(23, 5, 'Games', '', '1757230415_images (5).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `city`) VALUES
(1, 'divyaraj', '048b76814b1daa5e9a41c8a2607db956', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_user`
--
ALTER TABLE `front_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sitesettings`
--
ALTER TABLE `sitesettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `front_user`
--
ALTER TABLE `front_user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitesettings`
--
ALTER TABLE `sitesettings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
