-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2024 at 05:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lgutestdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admincredentials`
--

CREATE TABLE `admincredentials` (
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincredentials`
--

INSERT INTO `admincredentials` (`username`, `firstname`, `lastname`, `barangay`, `password`, `admin_code`) VALUES
('Sid', 'ad', 'min', '1', '$2y$10$24e44tzZEdA839ITyB./o.UEAolMinv2Ydn336O8IAFCLM/jsJnJe', ''),
('Stag', 'Bing', 'Bong', 'Bang', '$2y$10$iQSg7cl8jADsThnWm8rrju6MAR2U54S11wTCIPIs00Hw2/o6fxiH6', ''),
('water', 'asd', 'asda', 'asd', '$2y$10$AN952tia4Ld4iyJKEiUZfe9QetfLlijXl4sByU7ltdx3BEd4SH2Qa', ''),
('zxc', 'asd', 'asd', 'asd', '$2y$10$UOkVAnl0Cjzr79zdVTYxiuiNsUi/zt0l.9m09ri8kSypiEd3oHhzq', ''),
('admintest', 'admin', 'admin', 'admin', '$2y$10$RSy/x/XgaslDXdWfA.s5EutZBJKy4wuYkxoPfZfiijGBfktfzZ2A6', ''),
('admintestz', 'adasd', 'asdasd', 'asdasd', '$2y$10$/VjQ9knk8aSuEn7W5fQwveyjj4WY4KV7jI7/UX9OPo2WHsbcf1Pva', '');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcementID` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `Topic` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Images` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcementID`, `username`, `Topic`, `Description`, `Images`, `created_at`) VALUES
(3, 'Sid', 'HEH', 'CAT', 'would.jpg', '2024-10-13 18:26:12'),
(4, 'water', 'sonic', 'test long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontest long descriptiontes', '427971999_225426603990729_8886241880373014013_n.jpg', '2024-10-13 18:39:08'),
(5, 'water', 'lennon', 'x123', '464109284_560033689757211_8569353342527183854_n.jpg', '2024-10-26 22:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackid` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` varchar(250) NOT NULL,
  `images` varchar(255) NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackid`, `email`, `topic`, `description`, `location`, `images`, `submitted_date`) VALUES
('B482F7E', 'sid@gmail.com', 'Feedback', 'First feedback frfr', 'Franciscan Missionary Sisters of the Sacred Heart, #27, N. Reyes Street, Xavierville III, Loyola Heights, 3rd District, Quezon City, Eastern Manila District, Metro Manila, 1108, Philippines', 'lmfao.gif', '2024-10-13 18:22:16'),
('F4C5F1D', 'sid@gmail.com', 'Feedback', 'another feedback', 'H. R. Ocampo Street, Lambak 6-B, Krus na Ligas, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1101, Philippines', 'drive.jpg', '2024-10-13 18:22:56');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `email` varchar(200) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `location` varchar(250) NOT NULL,
  `images` varchar(255) NOT NULL,
  `reference_id` varchar(255) NOT NULL,
  `submitted_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Submitted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`email`, `topic`, `description`, `location`, `images`, `reference_id`, `submitted_date`, `last_updated`, `status`) VALUES
('sid@gmail.com', 'General Inquiry', 'First gen in', 'Department of Human Settlements and Urban Development/Human Settlements Adjudication Commission, Kalayaan Avenue, Old Capitol Site, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1101, Philippines', 'consequences.jpeg', 'REF-706BFA5', '2024-10-13 18:22:29', '2024-10-13 19:08:59', 'Completed'),
('sid@gmail.com', 'Complaint', 'complaint', 'Mahabagin Street, Teachers Village, Teachers Village West, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1101, Philippines', '11977144021448909699.gif', 'REF-B20CDB0', '2024-10-13 18:22:41', '2024-10-13 18:22:41', 'In-progress'),
('sid@gmail.com', 'General Inquiry', 'sdadssdsd', 'East Avenue Sewage Treatment Plant, Quezon Avenue, Central, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1100, Philippines', '', 'REF-1F43B81', '2024-10-26 22:51:34', '2024-10-26 22:51:34', 'Submitted'),
('sid@gmail.com', 'General Inquiry', '213123123', '9, Mahusay Street, UP Village, Diliman, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1101, Philippines', 'justdance.mp4', 'REF-EBE4E21', '2024-10-26 22:53:59', '2024-10-26 22:54:32', 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `usercredentials`
--

CREATE TABLE `usercredentials` (
  `username` varchar(25) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usercredentials`
--

INSERT INTO `usercredentials` (`username`, `firstname`, `lastname`, `email`, `password`) VALUES
('Sid', 'stagnant', 'water', 'sid@gmail.com', '$2y$10$ZDdYvrWV1Yuu3UtkXPhxhu8bo2/iwm.Kth298.jMLU81OM09G3w6C'),
('asd', 'asd', 'asd', 'water@gmail.com', '$2y$10$B74x1bK4ctFnFlIbWkx/2.St/.tMRAi6HOEhm4IfEgMn27LGyyJvS'),
('test', 'test', 'test', 'test@gmail.com', '$2y$10$fj/lDHxKL7Zmh40mPSk46eIusuQrjzbRCtePaoUQu0VfK1OWfivpG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcementID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcementID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
