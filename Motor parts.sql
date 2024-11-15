-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2024 at 04:21 PM
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
-- Database: `Motor parts`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`) VALUES
(1, 'Classic Potato Chips', 1.50, 100, 'https://wallpapers.com/images/hd/lays-potato-chips-in-yellow-background-43ouo5h6q7gnfyen.jpg'),
(2, 'Barbecue Flavored Chips', 1.75, 80, 'https://i.pinimg.com/1200x/4d/ec/44/4dec447cd6079eb93acee26f6d7f63a2.jpg'),
(3, 'Sour Cream & Onion Chips', 2.00, 50, 'https://www.tastyrewards.com/sites/default/files/2020-10/Packshots_Sour-Cream.jpg'),
(4, 'Cheddar Cheese Chips', 1.80, 60, 'https://down-vn.img.susercontent.com/file/vn-11134207-7qukw-letuf7rq79efe8'),
(5, 'Salt & Vinegar Chips', 1.60, 70, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSFsGWKs020kpxa0AvkfaveB8PV-0wstuvSVQ&s'),
(6, 'Spicy Jalapeno Chips', 2.20, 40, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1PmYbPvyC7QVOfwsezEYNvFxhGisgKNDBxm4c_hCzxllrvUIUIIqECo7vpTk_wXbZlCc&usqp=CAU'),
(7, 'Sweet Chili Chips', 2.10, 55, 'https://m.media-amazon.com/images/I/51jOgqHrWeL._AC_UF894,1000_QL80_.jpg'),
(8, 'Lime & Black Pepper Chips', 1.90, 65, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpAiTCZQB6KN7ukq0AJ3Q6huRGqL2AkHzQgg&s'),
(9, 'Kettle Cooked Sea Salt Chips', 2.50, 30, 'https://www.lays.com/sites/lays.com/files/2021-07/XL%20LKC%20Sea%20Salt%20Vinegar.png'),
(10, 'Honey Mustard Chips', 2.30, 45, 'https://i.pinimg.com/736x/47/73/2a/47732a395e83d949154df73e0a5e002a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `total_amount`, `payment`, `purchase_date`) VALUES
(1, 1, 13.65, 45.65, '2024-11-12 22:26:16'),
(2, 1, 13.65, 45.65, '2024-11-12 22:30:29'),
(3, 1, 10.00, 34.00, '2024-11-12 22:30:57'),
(4, 1, 10.00, 34.00, '2024-11-12 22:35:11'),
(5, 2, 19.50, 19.50, '2024-11-12 22:36:21'),
(6, 2, 19.50, 19.50, '2024-11-12 22:43:17'),
(7, 2, 6.00, 4534.00, '2024-11-12 22:47:56'),
(8, 2, 14.00, 3434.00, '2024-11-12 23:10:24'),
(9, 2, 14.00, 3434.00, '2024-11-12 23:14:54'),
(10, 2, 3.00, 45.00, '2024-11-12 23:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `first_login` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `first_login`) VALUES
(1, 'lord', '$2y$10$F0JdS1Ye.j3tAXAJ4lhXJOPCTDAutJF7AKTyeuaLLM5FloIb6DwJO', '2024-11-12 22:03:13', 1),
(2, 'opop', '$2y$10$sqrh5VOg8LZnvnTQuhR/h.fEG6.3BZ0tVx6uF.FnOWOA7MM8IPz52', '2024-11-12 22:35:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

CREATE TABLE `user_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_history`
--
ALTER TABLE `user_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_history`
--
ALTER TABLE `user_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_history`
--
ALTER TABLE `user_history`
  ADD CONSTRAINT `user_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;