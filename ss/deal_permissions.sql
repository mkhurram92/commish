-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2024 at 02:05 PM
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
-- Database: `commish_fimu`
--

-- --------------------------------------------------------

--
-- Table structure for table `deal_permissions`
--

CREATE TABLE `deal_permissions` (
  `id` int(11) NOT NULL,
  `deal_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deal_permissions`
--

INSERT INTO `deal_permissions` (`id`, `deal_id`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 512, 13, '2024-09-20 22:33:13', '2024-09-20 22:33:13'),
(4, 6145, 13, '2024-09-20 22:33:13', '2024-09-20 22:33:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deal_permissions`
--
ALTER TABLE `deal_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_deal_id` (`deal_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deal_permissions`
--
ALTER TABLE `deal_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deal_permissions`
--
ALTER TABLE `deal_permissions`
  ADD CONSTRAINT `fk_deal_permissions_deal_id` FOREIGN KEY (`deal_id`) REFERENCES `deals` (`id`),
  ADD CONSTRAINT `fk_deal_permissions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
