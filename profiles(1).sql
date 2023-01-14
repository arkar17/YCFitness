-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2022 at 04:09 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitness`
--

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `post_id`, `user_id`, `name`, `profile_image`, `cover_photo`, `bio`, `created_at`, `updated_at`) VALUES
(11, 0, 4, '', '', '1077850401.jpg', '', '2022-11-23 09:41:54', '2022-11-23 09:41:54'),
(12, 0, 4, '', '2121491819.jpg', '', '', '2022-11-23 09:44:10', '2022-11-23 09:44:10'),
(13, 0, 4, '', '', '2001405920.jpg', '', '2022-11-23 09:47:01', '2022-11-23 09:47:01'),
(14, 0, 9, '', '1486871058.jpg', '', '', '2022-11-23 09:53:38', '2022-11-23 09:53:38'),
(15, 0, 9, '', '1870714771.jpg', '', '', '2022-11-23 09:53:47', '2022-11-23 09:53:47'),
(16, 0, 9, '', '', '1503748194.jpg', '', '2022-11-23 09:53:56', '2022-11-23 09:53:56'),
(17, 0, 9, '', '19587943.jpg', '', '', '2022-11-23 09:54:07', '2022-11-23 09:54:07'),
(18, 0, 3, '', '1658148042.jpg', '', '', '2022-11-23 09:57:53', '2022-11-23 09:57:53'),
(19, 0, 3, '', '', '889380186.webp', '', '2022-11-23 09:58:02', '2022-11-23 09:58:02'),
(20, 0, 4, '', '', '480210281.jpg', '', '2022-11-24 02:39:43', '2022-11-24 02:39:43'),
(21, 0, 4, '', '', '52456037.jpg', '', '2022-11-24 02:39:53', '2022-11-24 02:39:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
