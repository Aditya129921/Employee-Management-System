-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2026 at 09:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_name` varchar(100) DEFAULT NULL,
  `manager_email` varchar(150) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `annual_budget` decimal(15,2) DEFAULT 0.00,
  `established_year` year(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `manager_name`, `manager_email`, `location`, `annual_budget`, `established_year`, `created_at`) VALUES
(1, 'IT', 'Software development, cloud infrastructure, cybersecurity and IT support.', 'Vikram Singh', 'vikram.singh@company.com', 'Block A, 3rd Floor, Tech Park, Bengaluru', 5000000.00, '2010', '2026-10-11 06:19:26'),
(2, 'HR', 'Talent acquisition, onboarding, employee welfare and performance management.', 'Priya Nair', 'priya.nair@company.com', 'Block B, 1st Floor, HQ, Chennai', 2000000.00, '2012', '2026-10-11 06:19:26'),
(3, 'Finance', 'Payroll, corporate budgeting, auditing, taxation and financial reporting.', 'Divya Pillai', 'divya.pillai@company.com', 'Block C, 2nd Floor, HQ, New Delhi', 3500000.00, '2011', '2026-10-11 06:19:26'),
(4, 'Marketing', 'Brand strategy, digital marketing, product launches and public relations.', 'Ananya Desai', 'ananya.desai@company.com', 'Block D, Ground Floor, HQ, Mumbai', 2800000.00, '2013', '2026-10-11 06:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` varchar(50) NOT NULL,
  `salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `joining_date` date DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `email`, `gender`, `phone`, `department`, `salary`, `joining_date`, `address`) VALUES
('1233456', 'Aditya', 'adi123@gmail.com', 'Male', '7086320617', 'Finance', 56788.00, '1995-12-14', 'hfhfjjf'),
('EMP001', 'Aarav Sarma', 'aarav.sharma@company.com', 'Male', '9876543210', 'IT', 72000.00, '2021-03-15', '12 MG Road, Bengaluru, Karnataka'),
('EMP002', 'Priya Nair', 'priya.nair@company.com', 'Female', '9845612378', 'HR', 58000.00, '2020-07-01', '45 Anna Salai, Chennai, Tamil Nadu'),
('EMP003', 'Rohan Mehta', 'rohan.mehta@company.com', 'Male', '9123456789', 'Finance', 85000.00, '2019-11-20', '78 Linking Road, Mumbai, Maharashtra'),
('EMP004', 'Sneha Reddy', 'sneha.reddy@company.com', 'Female', '9988776655', 'Marketing', 63000.00, '2022-01-10', '34 Banjara Hills, Hyderabad, Telangana'),
('EMP005', 'Karthik Iyer', 'karthik.iyer@company.com', 'Male', '9765432108', 'IT', 91000.00, '2018-06-05', '21 T Nagar, Chennai, Tamil Nadu'),
('EMP006', 'Divya Pillai', 'divya.pillai@company.com', 'Female', '9654321087', 'Finance', 76500.00, '2021-09-30', '56 Connaught Place, New Delhi'),
('EMP007', 'Aditya Joshi', 'aditya.joshi@company.com', 'Male', '9543210976', 'Marketing', 54000.00, '2023-02-14', '89 FC Road, Pune, Maharashtra'),
('EMP008', 'Meera Krishnan', 'meera.krishnan@company.com', 'Female', '9432109865', 'HR', 61000.00, '2020-04-22', '11 Salt Lake, Kolkata, West Bengal'),
('EMP009', 'Vikram Singh', 'vikram.singh@company.com', 'Male', '9321098754', 'IT', 110000.00, '2017-08-11', '67 Sector 15, Noida, Uttar Pradesh'),
('EMP010', 'Ananya Desai', 'ananya.desai@company.com', 'Female', '9210987643', 'Marketing', 68000.00, '2022-11-03', '23 CG Road, Ahmedabad, Gujarat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
