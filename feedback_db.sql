-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 12:20 AM
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
-- Database: `feedback_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rating` int(1) NOT NULL,
  `comments` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `comments`, `file`, `submission_time`) VALUES
(1, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 4, '', '', '2025-02-22 18:12:24'),
(2, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 5, 'very nice', '', '2025-02-22 18:12:58'),
(3, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 5, 'good design', '', '2025-02-22 19:21:45'),
(4, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 5, 'good design', '', '2025-02-22 19:22:27'),
(5, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 5, 'good design', 'uploads/p.jpeg', '2025-02-22 19:24:00'),
(6, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 0, 'good one no improvement suggestions', '', '2025-02-22 19:29:33'),
(7, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 4, 'good one no improvement suggestions', '', '2025-02-22 19:30:16'),
(8, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 4, 'good design keep up designers', '', '2025-02-22 19:45:40'),
(9, 'Kelvin ghh Otenyo', 'kelvinotenyo43@gmail.com', 5, '', '', '2025-02-22 19:46:25'),
(10, 'Kelvin ghh Otenyo', 'kelvinotenyo43@gmail.com', 4, 'good good ', '', '2025-02-22 19:50:48'),
(11, 'Kelvin ghh Otenyo', 'kelvinotenyo43@gmail.com', 3, 'good design', '', '2025-02-22 19:56:44'),
(12, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 2, 'good design try\\r\\n', '', '2025-02-22 20:20:15'),
(13, 'KELVIN OTENYO', 'tiseeonlinejobs7@yahoo.com', 3, 'good design perfect for', '', '2025-02-22 21:05:34'),
(14, 'KELVIN OTENYOm,nk', 'tiseeonlinejobs7@yahoo.com', 3, 'good designm,k', '', '2025-02-22 21:40:24'),
(15, 'm,jj', 'tiseeonlinejobs7@yahoo.com', 2, 'ju lkkl', '', '2025-02-22 22:20:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
