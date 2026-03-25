-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 09:57 AM
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
-- Database: `slais`
--

-- --------------------------------------------------------

--
-- Table structure for table `myua_user`
--

CREATE TABLE `myua_user` (
  `recid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `hash_password` varchar(200) NOT NULL,
  `hash_value` varchar(150) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `division` varchar(50) NOT NULL,
  `section` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  `cert_tag` int(11) DEFAULT 0,
  `is_ppmp_signatory` int(11) NOT NULL DEFAULT 0,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `myua_user`
--

INSERT INTO `myua_user` (`recid`, `username`, `hash_password`, `hash_value`, `full_name`, `division`, `section`, `position`, `cert_tag`, `is_ppmp_signatory`, `added_at`, `added_by`, `is_active`) VALUES
(1, 'FAD-ROMANA', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ROMANA L. LLAMAS', 'FAD', 'BUDGET SECTION', 'ADMINISTRATIVE OFFICER V', 2, 0, '2025-04-23 07:21:50', 'admin', 1),
(6, 'ADMIN-KYLE', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'KYLE ANDRAE ALINO', 'FAD', 'CDS', 'PROJECT TECHNICAL SPECIALIST I', 0, 0, '2025-05-16 08:55:10', 'admin', 1),
(7, 'ADMIN-JOVY', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'JOVY S. MEDINA', 'FAD', 'CDS', 'PROJECT ADMINISTRATIVE OFFICER IV', 0, 0, '2025-05-16 09:30:39', 'admin', 1),
(8, 'NFRDD-ROSEMARIE', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ROSEMARIE J. DUMAG, RCh., MSc.', 'NFRDD', 'OFFICE OF THE DIVISION CHIEF', 'Chief SRS', 1, 1, '2025-07-14 13:44:32', 'admin', 1),
(9, 'BS-MILDRED', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MILDRED D. VILLANUEVA', 'FAD', '-', 'ADMINISTRATIVE OFFICER IV', 0, 0, '2025-07-14 13:58:09', 'admin', 1),
(10, 'BS-ROSEFIL', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ROSEFIL J. MALINAO', 'FAD', 'BUDGET SECTION', '-', 0, 0, '2025-07-14 13:58:38', 'admin', 1),
(11, 'FAD-ALEXIS', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ALEXIS M. ORTIZ', 'FAD', '-', 'CHIEF ADMINISTRATIVE OFFICER', 1, 1, '2025-07-14 15:23:50', 'CDS-KYLE', 1),
(12, 'OD-LUCIEDEN', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ATTY. LUCIEDEN G. RAZ', 'Office of the Director', '-', 'Director III & Officer-in-charge', 1, 0, '2025-08-06 09:46:06', 'CDS-KYLE', 1),
(13, 'FAD-ALEXIS', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ALEXIS M. ORTIZ', 'FAD', '-', 'Chief Administrative Officer', 0, 0, '2025-08-06 09:46:49', 'CDS-KYLE', 1),
(14, 'TDSTSD-MILFLOR', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MILFLOR S. GONZALES, Ph.D', 'TDSTSD', '-', 'Chief SRS', 1, 0, '2025-08-06 09:47:48', 'CDS-KYLE', 1),
(15, 'PO-DIVORAH', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'DIVORAH V. AGUILA', '-', '-', 'Planning Officer IV', 1, 1, '2025-08-06 09:49:41', 'CDS-KYLE', 1),
(16, 'SLG-LEAH', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'LEAH C. DAJAY', 'SLG', '-', 'Supervising SRS', 1, 1, '2025-08-06 09:50:14', 'CDS-KYLE', 1),
(17, 'NAMD-LILIBETH', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MA. LILIBETH P. DASCO', 'NAMD', '-', 'Supervising SRS', 1, 0, '2025-08-12 10:17:07', 'CDS-KYLE', 1),
(18, 'PPT-USER', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'GENERIC PROPERTY USER', 'PROPERTY', 'PROPERTY', 'PROPERTY USER', 0, 0, '2025-10-09 10:06:04', 'ADMIN-KYLE', 1),
(19, 'NAMD-MILDRED', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MILDRED O. GUIRINDOLA, Ph. D.', 'NAMD', '-', 'Chief SRS', 1, 1, '2025-10-21 16:18:42', 'ADMIN-KYLE', 1),
(20, 'BS-ANN', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MARY ANN DELA RAMA', 'FAD', 'BUDGET SECTION', 'Project Admin Assistant I', 0, 0, '2025-12-02 10:38:43', 'ADMIN-KYLE', 1),
(21, 'CDS-OJT', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'OJT ACCOUNT - PIA', 'FAD', 'CDS', 'OJT', 0, 0, '2025-12-03 15:15:23', 'ADMIN-KYLE', 1),
(22, 'TDSTSD-SALVADOR', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'SALVADOR R. SERRANO', 'TDSTSD', '-', 'Supervising SRS & Officer-in-charge', 1, 1, '2025-12-11 10:12:16', 'ADMIN-KYLE', 1),
(23, 'FAD-JESTER', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'JESTER C. VIRIÑA', 'FAD', '-', 'SUPERVISING AO, FAD/SAU', 1, 0, '2026-01-28 09:35:05', 'ADMIN-KYLE', 1),
(24, 'NAMD-EVA', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'EVA A. GOYENA', 'NAMD', '-', 'SUPERVISING SRS, NIEPS & SCIENTIST I', 1, 0, '2026-01-28 09:35:38', 'ADMIN-KYLE', 1),
(25, 'NAMD-LILIBETH', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MA. LILIBETH P. DASCO', 'NAMD', '-', 'SUPERVISING SRS, NAS', 1, 0, '2026-01-28 09:36:05', 'ADMIN-KYLE', 1),
(26, 'NAMD-GLEN', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'GLEN MELVIN P. GIRONELLA', 'NAMD', '-', 'SUPERVISING SRS, NSIS', 1, 0, '2026-01-28 09:36:26', 'ADMIN-KYLE', 1),
(27, 'NAMD-MAE', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MAE ANN S.A. JAVIER', 'NAMD', '-', 'SENIOR SRS, NSIS', 1, 0, '2026-01-28 09:37:27', 'ADMIN-KYLE', 1),
(28, 'NAMD-STEPHANI ', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MA. STEPHANI N. PARANI', 'NAMD', '-', 'SENIOR SRS, NAS', 1, 0, '2026-01-28 09:37:48', 'ADMIN-KYLE', 1),
(29, 'NAMD-MAYLENE', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'MAYLENE P. CAJUCOM', 'NAMD', '-', 'SRS II, NAS', 1, 0, '2026-01-28 09:38:08', 'ADMIN-KYLE', 1),
(30, 'NAMD-JEMN', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'JEMN D. SERRANO', 'NAMD', '-', 'SRS II, NAS', 1, 0, '2026-01-28 09:38:36', 'ADMIN-KYLE', 1),
(31, 'NAMD-RICA', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'RICAMAE V. LARRAZABAL', 'NAMD', '-', 'SRS II, NSIS', 1, 0, '2026-01-28 09:38:55', 'ADMIN-KYLE', 1),
(32, 'NAMD-ROWENA', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ROWENA VIAJAR', 'NAMD', '-', 'SRS II, NIEPS', 1, 0, '2026-01-28 09:39:14', 'ADMIN-KYLE', 1),
(33, 'NAMD-EMILY', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'EMILY O. RONGAVILLA', 'NAMD', '-', 'SRS II, NIEPS', 1, 0, '2026-01-28 09:39:36', 'ADMIN-KYLE', 1),
(34, 'NAMD-ROD', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'ROD PAULO B. LORENZO', 'NAMD', '-', 'SRS I, NAS', 1, 0, '2026-01-28 09:40:01', 'ADMIN-KYLE', 1),
(35, 'NAMD-CHEDER', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', '123456', 'CHEDER D. SUMANGUE', 'NAMD', '-', 'SRS I, NSIS', 1, 0, '2026-01-28 09:40:18', 'ADMIN-KYLE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loans`
--

CREATE TABLE `tbl_loans` (
  `loan_id` int(25) NOT NULL,
  `member_id` int(25) NOT NULL,
  `loan_type` varchar(100) NOT NULL,
  `loan_amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(15,2) NOT NULL,
  `term_months` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `maturity_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `loan_comakers` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_loans`
--

INSERT INTO `tbl_loans` (`loan_id`, `member_id`, `loan_type`, `loan_amount`, `interest_rate`, `term_months`, `start_date`, `maturity_date`, `status`, `loan_comakers`, `created_by`, `created_at`) VALUES
(4, 1, 'Personal Loan', 100000.00, 10.00, 6, '2026-01-01', '2026-06-01', 'Pending', '3', 'ADMIN-KYLE', '2026-03-24 10:22:18'),
(5, 2, 'Home Loan', 500000.00, 5.00, 12, '2026-02-02', '2027-02-02', 'Pending', '3', 'ADMIN-KYLE', '2026-03-24 11:17:21'),
(8, 3, 'Personal Loan', 1000000.00, 10.00, 12, '2026-03-24', '2027-03-24', 'Pending', '2', 'ADMIN-KYLE', '2026-03-24 14:06:51'),
(9, 4, 'Home Loan', 2000000.00, 20.00, 12, '2026-03-24', '2027-03-24', 'Pending', '5', 'ADMIN-KYLE', '2026-03-24 14:12:39'),
(11, 5, 'Personal Loan', 1000000.00, 10.00, 12, '2026-03-24', '2027-03-24', 'Pending', '1', 'ADMIN-KYLE', '2026-03-24 15:24:29'),
(12, 6, 'Personal Loan', 1000000.00, 10.00, 12, '2026-03-24', '2027-03-24', 'Pending', '1', 'ADMIN-KYLE', '2026-03-24 15:33:12'),
(13, 10, 'Personal Loan', 3000000.00, 15.00, 12, '2026-03-25', '2027-02-25', 'Pending', '1', 'ADMIN-KYLE', '2026-03-25 16:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loans_ammortization`
--

CREATE TABLE `tbl_loans_ammortization` (
  `ammortization_id` int(25) NOT NULL,
  `loan_id` int(25) NOT NULL,
  `member_id` int(25) NOT NULL,
  `period` int(10) NOT NULL,
  `payment_date` date NOT NULL,
  `beginning_balance` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `principal` decimal(15,2) NOT NULL,
  `payment` decimal(15,2) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `ending_balance` decimal(15,2) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_loans_ammortization`
--

INSERT INTO `tbl_loans_ammortization` (`ammortization_id`, `loan_id`, `member_id`, `period`, `payment_date`, `beginning_balance`, `interest`, `principal`, `payment`, `payment_status`, `ending_balance`, `created_by`, `created_at`) VALUES
(1, 4, 1, 1, '2026-01-01', 100000.00, 833.33, 16322.81, 17156.14, 'unpaid', 83677.19, 'ADMIN-KYLE', '2026-03-24 02:22:18'),
(2, 4, 1, 2, '2026-02-01', 83677.19, 697.31, 16458.83, 17156.14, 'unpaid', 67218.36, 'ADMIN-KYLE', '2026-03-24 02:22:18'),
(3, 4, 1, 3, '2026-03-01', 67218.36, 560.15, 16595.99, 17156.14, 'unpaid', 50622.38, 'ADMIN-KYLE', '2026-03-24 02:22:18'),
(4, 4, 1, 4, '2026-04-01', 50622.38, 421.85, 16734.29, 17156.14, 'unpaid', 33888.09, 'ADMIN-KYLE', '2026-03-24 02:22:18'),
(5, 4, 1, 5, '2026-05-01', 33888.09, 282.40, 16873.74, 17156.14, 'unpaid', 17014.35, 'ADMIN-KYLE', '2026-03-24 02:22:18'),
(6, 4, 1, 6, '2026-06-01', 17014.35, 141.79, 17014.35, 17156.14, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-24 02:22:18'),
(7, 5, 2, 1, '2026-02-02', 500000.00, 2083.33, 40720.41, 42803.74, 'unpaid', 459279.59, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(8, 5, 2, 2, '2026-03-02', 459279.59, 1913.66, 40890.08, 42803.74, 'unpaid', 418389.52, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(9, 5, 2, 3, '2026-04-02', 418389.52, 1743.29, 41060.45, 42803.74, 'unpaid', 377329.07, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(10, 5, 2, 4, '2026-05-02', 377329.07, 1572.20, 41231.54, 42803.74, 'unpaid', 336097.53, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(11, 5, 2, 5, '2026-06-02', 336097.53, 1400.41, 41403.33, 42803.74, 'unpaid', 294694.19, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(12, 5, 2, 6, '2026-07-02', 294694.19, 1227.89, 41575.85, 42803.74, 'unpaid', 253118.35, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(13, 5, 2, 7, '2026-08-02', 253118.35, 1054.66, 41749.08, 42803.74, 'unpaid', 211369.26, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(14, 5, 2, 8, '2026-09-02', 211369.26, 880.71, 41923.04, 42803.74, 'unpaid', 169446.23, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(15, 5, 2, 9, '2026-10-02', 169446.23, 706.03, 42097.71, 42803.74, 'unpaid', 127348.51, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(16, 5, 2, 10, '2026-11-02', 127348.51, 530.62, 42273.12, 42803.74, 'unpaid', 85075.39, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(17, 5, 2, 11, '2026-12-02', 85075.39, 354.48, 42449.26, 42803.74, 'unpaid', 42626.13, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(18, 5, 2, 12, '2027-01-02', 42626.13, 177.61, 42626.13, 42803.74, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-24 03:17:21'),
(19, 8, 3, 1, '2026-03-24', 1000000.00, 8333.33, 79582.55, 87915.89, 'unpaid', 920417.45, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(20, 8, 3, 2, '2026-04-24', 920417.45, 7670.15, 80245.74, 87915.89, 'unpaid', 840171.70, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(21, 8, 3, 3, '2026-05-24', 840171.70, 7001.43, 80914.46, 87915.89, 'unpaid', 759257.25, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(22, 8, 3, 4, '2026-06-24', 759257.25, 6327.14, 81588.74, 87915.89, 'unpaid', 677668.50, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(23, 8, 3, 5, '2026-07-24', 677668.50, 5647.24, 82268.65, 87915.89, 'unpaid', 595399.85, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(24, 8, 3, 6, '2026-08-24', 595399.85, 4961.67, 82954.22, 87915.89, 'unpaid', 512445.63, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(25, 8, 3, 7, '2026-09-24', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(26, 8, 3, 8, '2026-10-24', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(27, 8, 3, 9, '2026-11-24', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(28, 8, 3, 10, '2026-12-24', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(29, 8, 3, 11, '2027-01-24', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(30, 8, 3, 12, '2027-02-24', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-24 06:06:51'),
(31, 9, 4, 1, '2026-03-24', 2000000.00, 33333.33, 151935.68, 185269.01, 'Paid', 1848064.32, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(32, 9, 4, 2, '2026-04-24', 1848064.32, 30801.07, 154467.94, 185269.01, 'Paid', 1693596.38, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(33, 9, 4, 3, '2026-05-24', 1693596.38, 28226.61, 157042.41, 185269.01, 'Paid', 1536553.98, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(34, 9, 4, 4, '2026-06-24', 1536553.98, 25609.23, 159659.78, 185269.01, 'unpaid', 1376894.20, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(35, 9, 4, 5, '2026-07-24', 1376894.20, 22948.24, 162320.78, 185269.01, 'unpaid', 1214573.42, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(36, 9, 4, 6, '2026-08-24', 1214573.42, 20242.89, 165026.12, 185269.01, 'unpaid', 1049547.30, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(37, 9, 4, 7, '2026-09-24', 1049547.30, 17492.46, 167776.56, 185269.01, 'unpaid', 881770.74, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(38, 9, 4, 8, '2026-10-24', 881770.74, 14696.18, 170572.83, 185269.01, 'unpaid', 711197.91, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(39, 9, 4, 9, '2026-11-24', 711197.91, 11853.30, 173415.71, 185269.01, 'unpaid', 537782.20, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(40, 9, 4, 10, '2026-12-24', 537782.20, 8963.04, 176305.98, 185269.01, 'unpaid', 361476.22, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(41, 9, 4, 11, '2027-01-24', 361476.22, 6024.60, 179244.41, 185269.01, 'unpaid', 182231.81, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(42, 9, 4, 12, '2027-02-24', 182231.81, 3037.20, 182231.81, 185269.01, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-24 06:12:39'),
(55, 11, 5, 1, '2026-03-24', 1000000.00, 8333.33, 79582.55, 87915.89, 'Paid', 920417.45, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(56, 11, 5, 2, '2026-04-24', 920417.45, 7670.15, 80245.74, 87915.89, 'Paid', 840171.70, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(57, 11, 5, 3, '2026-05-24', 840171.70, 7001.43, 80914.46, 87915.89, 'unpaid', 759257.25, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(58, 11, 5, 4, '2026-06-24', 759257.25, 6327.14, 81588.74, 87915.89, 'unpaid', 677668.50, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(59, 11, 5, 5, '2026-07-24', 677668.50, 5647.24, 82268.65, 87915.89, 'unpaid', 595399.85, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(60, 11, 5, 6, '2026-08-24', 595399.85, 4961.67, 82954.22, 87915.89, 'unpaid', 512445.63, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(61, 11, 5, 7, '2026-09-24', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(62, 11, 5, 8, '2026-10-24', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(63, 11, 5, 9, '2026-11-24', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(64, 11, 5, 10, '2026-12-24', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(65, 11, 5, 11, '2027-01-24', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(66, 11, 5, 12, '2027-02-24', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-24 07:24:29'),
(67, 12, 6, 1, '2026-03-24', 1000000.00, 8333.33, 79582.55, 87915.89, 'Paid', 920417.45, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(68, 12, 6, 2, '2026-04-24', 920417.45, 7670.15, 80245.74, 87915.89, 'Paid', 840171.70, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(69, 12, 6, 3, '2026-05-24', 840171.70, 7001.43, 80914.46, 87915.89, 'unpaid', 759257.25, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(70, 12, 6, 4, '2026-06-24', 759257.25, 6327.14, 81588.74, 87915.89, 'unpaid', 677668.50, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(71, 12, 6, 5, '2026-07-24', 677668.50, 5647.24, 82268.65, 87915.89, 'unpaid', 595399.85, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(72, 12, 6, 6, '2026-08-24', 595399.85, 4961.67, 82954.22, 87915.89, 'unpaid', 512445.63, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(73, 12, 6, 7, '2026-09-24', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(74, 12, 6, 8, '2026-10-24', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(75, 12, 6, 9, '2026-11-24', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(76, 12, 6, 10, '2026-12-24', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(77, 12, 6, 11, '2027-01-24', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(78, 12, 6, 12, '2027-02-24', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-24 07:33:12'),
(79, 13, 10, 1, '2026-03-25', 3000000.00, 37500.00, 233274.94, 270774.94, 'Paid', 2766725.06, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(80, 13, 10, 2, '2026-04-25', 2766725.06, 34584.06, 236190.87, 270774.94, 'unpaid', 2530534.19, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(81, 13, 10, 3, '2026-05-25', 2530534.19, 31631.68, 239143.26, 270774.94, 'unpaid', 2291390.93, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(82, 13, 10, 4, '2026-06-25', 2291390.93, 28642.39, 242132.55, 270774.94, 'unpaid', 2049258.38, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(83, 13, 10, 5, '2026-07-25', 2049258.38, 25615.73, 245159.21, 270774.94, 'unpaid', 1804099.17, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(84, 13, 10, 6, '2026-08-25', 1804099.17, 22551.24, 248223.70, 270774.94, 'unpaid', 1555875.47, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(85, 13, 10, 7, '2026-09-25', 1555875.47, 19448.44, 251326.49, 270774.94, 'unpaid', 1304548.98, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(86, 13, 10, 8, '2026-10-25', 1304548.98, 16306.86, 254468.07, 270774.94, 'unpaid', 1050080.91, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(87, 13, 10, 9, '2026-11-25', 1050080.91, 13126.01, 257648.93, 270774.94, 'unpaid', 792431.98, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(88, 13, 10, 10, '2026-12-25', 792431.98, 9905.40, 260869.54, 270774.94, 'unpaid', 531562.44, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(89, 13, 10, 11, '2027-01-25', 531562.44, 6644.53, 264130.41, 270774.94, 'unpaid', 267432.04, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(90, 13, 10, 12, '2027-02-25', 267432.04, 3342.90, 267432.04, 270774.94, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-25 08:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loans_payment`
--

CREATE TABLE `tbl_loans_payment` (
  `payment_id` int(25) NOT NULL,
  `loan_id` int(25) NOT NULL,
  `member_id` int(25) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `principal` decimal(15,2) NOT NULL,
  `total_payment` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_loans_payment`
--

INSERT INTO `tbl_loans_payment` (`payment_id`, `loan_id`, `member_id`, `interest`, `principal`, `total_payment`, `payment_date`, `created_by`, `created_at`) VALUES
(1, 9, 4, 33333.33, 151935.68, 185269.01, '2026-03-24', 'ADMIN-KYLE', '2026-03-24 15:17:38'),
(2, 9, 4, 30801.07, 154467.94, 185269.01, '2026-04-24', 'ADMIN-KYLE', '2026-03-24 15:19:06'),
(4, 11, 5, 8333.33, 79582.55, 87915.89, '2026-03-24', 'ADMIN-KYLE', '2026-03-24 15:24:43'),
(5, 9, 4, 28226.61, 157042.41, 185269.01, '2026-05-24', 'ADMIN-KYLE', '2026-03-24 15:31:53'),
(6, 11, 5, 7670.15, 80245.74, 87915.89, '2026-04-24', 'ADMIN-KYLE', '2026-03-24 15:32:35'),
(7, 12, 6, 8333.33, 79582.55, 87915.89, '2026-03-24', 'ADMIN-KYLE', '2026-03-24 15:33:40'),
(8, 12, 6, 7670.15, 80245.74, 87915.89, '2026-04-24', 'ADMIN-KYLE', '2026-03-24 16:00:57'),
(9, 13, 10, 37500.00, 233274.94, 270774.94, '2026-03-25', 'ADMIN-KYLE', '2026-03-25 16:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_members`
--

CREATE TABLE `tbl_members` (
  `member_id` int(25) NOT NULL,
  `member_no` int(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `hash_password` varchar(100) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_members`
--

INSERT INTO `tbl_members` (`member_id`, `member_no`, `first_name`, `last_name`, `middle_name`, `address`, `contact_number`, `email`, `username`, `password`, `hash_password`, `created_by`, `created_at`) VALUES
(1, 12301293, 'KYLE ANDRAE', 'ALIÑO', 'POSADAS', '123 address hehe caloocan city rararrara', '09158018602', 'kylealino@gmail.com', 'ADMIN-KYLE', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-22 10:00:14'),
(2, 120312031, 'JAMIE', 'CRUZ', 'SANTIAGO', 'adsadasdasda', '192301231', 'jamiecruz@gmail.com', '', '', '', '', '2026-03-22 10:07:50'),
(3, 2147483647, 'BRYAN', 'ALINO', 'POSADS', 'adsadasdasdaasdasdasdasdasdasdasda', '1231231', 'bryan@gmail.com', '', '', '', '', '2026-03-22 10:08:02'),
(4, 120301231, 'lee', 'alino', 'posasd', 'asdasdas', '1203120', 'lee@gmail.com', '', '', '', '', '2026-03-22 10:09:18'),
(5, 2147483647, 'JOVY ', 'MEDINA', 'S', '123 test address taguig city', '109513266', 'jovymedina@gmail.com', 'ADMIN-JOVY', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-23 09:47:56'),
(6, 2147483647, 'ALEX', 'HABIG', 'XANDER', '123 ilang ilang st baesa caloocan city', '910231', 'alexhabig@gmail.com', 'ADMIN-ALEX', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-23 10:57:23'),
(7, 232565163, 'JEN', 'CRUZ', 'SAIRA', '123 ilang ilang st. baesa caloocan city', '0920301301', 'jencruz@gmail.com', 'TEST-JEN', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:37:57'),
(8, 2147483647, 'JEBOY', 'SANTIAGO', 'CRUZ', '237 A. Mabini St', '09182930120', 'jeboy@gmail.com', 'TEST-JEBOY', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:38:42'),
(9, 232656323, 'MICHELLE', 'SANTIAGO', 'CRUZ', '123 test address quezon city', '0910230102', 'michelle@gmail.com', 'TEST-MICHELLE', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:39:20'),
(10, 2147483647, 'ANDRAE', 'POSADAS', 'TUWAI', '203 Caloocan City', '09158018602', 'andrae@gmail.com', 'TEST-ANDRAE', '123456', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:42:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `myua_user`
--
ALTER TABLE `myua_user`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tbl_loans`
--
ALTER TABLE `tbl_loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `tbl_loans_ammortization`
--
ALTER TABLE `tbl_loans_ammortization`
  ADD PRIMARY KEY (`ammortization_id`);

--
-- Indexes for table `tbl_loans_payment`
--
ALTER TABLE `tbl_loans_payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD PRIMARY KEY (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `myua_user`
--
ALTER TABLE `myua_user`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_loans`
--
ALTER TABLE `tbl_loans`
  MODIFY `loan_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_loans_ammortization`
--
ALTER TABLE `tbl_loans_ammortization`
  MODIFY `ammortization_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `tbl_loans_payment`
--
ALTER TABLE `tbl_loans_payment`
  MODIFY `payment_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_members`
--
ALTER TABLE `tbl_members`
  MODIFY `member_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
