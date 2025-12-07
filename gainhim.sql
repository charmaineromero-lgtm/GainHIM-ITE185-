-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 02:53 PM
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
-- Database: `gainhim`
--

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
  `journal_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `is_community` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal`
--

INSERT INTO `journal` (`journal_id`, `id`, `title`, `content`, `is_community`, `created_at`) VALUES
(1, 6, 'Cha 2025', 'The Lord is rich to all who call upon Him!', 0, '2025-12-07 03:04:15'),
(2, 6, '3am thoughts', 'there may be times that we feel so far away from the Lord...but i enjoyed that He is constantly calling us to come back to Him. Thank You, for Your constant visitation, Lord.', 0, '2025-12-07 03:10:45'),
(3, 5, 'kellycxz', 'hahahhahaeeeee', 0, '2025-12-07 03:21:15'),
(5, 7, '3am thoughts', 'there may be days, when im lost and afraid, and it seems that i can\'t find my way...\r\nbut little did i know that Christ is interceding for me, with words i cannot utter.', 0, '2025-12-07 03:35:55'),
(6, 7, 'random', 'love the Lord!', 0, '2025-12-07 03:36:34'),
(12, 7, 'trial edit', 'dfsd', 0, '2025-12-07 04:06:06'),
(13, 7, 'sc', 'cs', 0, '2025-12-07 21:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `journal_tags`
--

CREATE TABLE `journal_tags` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal_tags`
--

INSERT INTO `journal_tags` (`id`, `journal_id`, `tag_id`) VALUES
(5, 1, 2),
(3, 3, 2),
(6, 5, 4),
(10, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'kelly@gmail.com', 'b5de5f2f9cd3f6c9113962c18abee08ce40f7c1e299a5f55a5cf75f645bcf1352376f943ab0ac4afafae738d3055669f0e48', '2025-12-06 18:03:34'),
(2, 'kelly@gmail.com', 'bf3a62e39be88e070bd202a129d48ac720d051c81e44303d5315e7943302a1fc9860c692c2160cf2f7addc057d4b5f3bb10e', '2025-12-06 18:03:47'),
(3, 'kelly@gmail.com', '63b7ab26ad6db5f80ca1893b5411e5ca34bdcc891aeeb605110877f7ccfd1f5b45018d61d029582411eff4d8812599ad37b0', '2025-12-06 18:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag_name`) VALUES
(1, 'Faith'),
(2, 'Joy'),
(3, 'Peace'),
(4, 'Hope');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`id`, `username`, `email`, `password`) VALUES
(1, 'charmaine', 'cha2025@gmail.com', '$2y$10$1iF0CWMnEXGD.jjmpQKeg.yff.y9HPz.fO4Kit5FwS7'),
(2, 'kev', 'kev@gmail.com', '$2y$10$mXTRXzJ7/ov4/csLzOFmH.Yph.3FNxQsQI.ye1M3X6c'),
(3, 'kevz', 'kevz@gmail.com', '$2y$10$WI10sp14tHXOXXtHJGglIO5xmnW8P0zUwfSPy6c8DEr'),
(4, 'hi', 'hi@gmail.com', '$2y$10$vJBIsJi7aJcJxjBchA8bcOWtN0TgF58Q2jyA6q6ITcy'),
(5, 'kelly_', 'kelly@gmail.com', '$2y$10$UjFnMQ4JKXdiQe.0NlAfHOThh/QvB49rt/wRxFRnkLZXJWs2Ha526'),
(6, 'shhh', 'shhh@gmail.com', '$2y$10$bi/c1Ux02o4lRoWzN.9h7.7ziTpt82G061VLxplh/Mrn/ZjCDDZQ2'),
(7, 'Maine', 'maine@gmail.com', '$2y$10$oyn5uy2yhIVMwHATSKwUpOnSWbUvnm3GNhLU5mv6ZSnOzzQ4eGpgC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`journal_id`),
  ADD KEY `idx_is_community` (`is_community`);

--
-- Indexes for table `journal_tags`
--
ALTER TABLE `journal_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_journal_tag` (`journal_id`,`tag_id`),
  ADD KEY `fk_tag` (`tag_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `journal`
--
ALTER TABLE `journal`
  MODIFY `journal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `journal_tags`
--
ALTER TABLE `journal_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `journal_tags`
--
ALTER TABLE `journal_tags`
  ADD CONSTRAINT `fk_journal` FOREIGN KEY (`journal_id`) REFERENCES `journal` (`journal_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
