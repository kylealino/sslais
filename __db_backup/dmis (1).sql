-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 03:51 AM
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
-- Database: `dmis`
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
-- Table structure for table `tbl_coa`
--

CREATE TABLE `tbl_coa` (
  `account_id` int(11) NOT NULL,
  `account_code` varchar(10) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_type` enum('Asset','Liability','Equity','Revenue','Expense') NOT NULL,
  `parent_code` varchar(10) DEFAULT NULL,
  `is_active` int(1) DEFAULT 1,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_coa`
--

INSERT INTO `tbl_coa` (`account_id`, `account_code`, `account_name`, `account_type`, `parent_code`, `is_active`, `created_by`, `created_at`) VALUES
(33, '1000', 'Current Assets', 'Asset', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(34, '1010', 'Cash on Hand', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(35, '1020', 'Cash in Bank', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(36, '1030', 'Accounts Receivable', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(37, '1040', 'Inventory', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(38, '1050', 'Prepaid Expenses', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(39, '1500', 'Non-Current Assets', 'Asset', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(40, '1510', 'Property, Plant & Equipment', 'Asset', '1500', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(41, '1520', 'Accumulated Depreciation', 'Asset', '1500', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(42, '1530', 'Intangible Assets', 'Asset', '1500', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(43, '2000', 'Current Liabilities', 'Liability', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(44, '2010', 'Accounts Payable', 'Liability', '2000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(45, '2020', 'Accrued Expenses', 'Liability', '2000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(46, '2030', 'Taxes Payable', 'Liability', '2000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(47, '2500', 'Long-Term Liabilities', 'Liability', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(48, '2510', 'Loans Payable', 'Liability', '2500', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(49, '2520', 'Bonds Payable', 'Liability', '2500', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(50, '3000', 'Equity', 'Equity', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(51, '3010', 'Owner’s Capital', 'Equity', '3000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(52, '3020', 'Retained Earnings', 'Equity', '3000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(53, '3030', 'Drawingsasdasdasdasda', 'Equity', '3000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(54, '4000', 'Revenue', 'Revenue', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(55, '4010', 'Sales Revenue', 'Revenue', '4000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(56, '4020', 'Service Income', 'Revenue', '4000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(57, '4030', 'Other Income', 'Revenue', '4000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(58, '5000', 'Expenses', 'Expense', NULL, 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(59, '5010', 'Salaries Expense', 'Expense', '5000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(60, '5020', 'Rent Expense', 'Expense', '5000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(61, '5030', 'Utilities Expense', 'Expense', '5000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(62, '5040', 'Office Supplies', 'Expense', '5000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(63, '5050', 'Depreciation Expense', 'Expense', '5000', 1, 'ADMIN-KYLE', '2026-03-30 16:56:19'),
(64, '1060', 'test', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 17:13:41'),
(65, '1070', 'test2', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 17:14:29'),
(66, '1080', 'rararara', 'Asset', '1000', 1, 'ADMIN-KYLE', '2026-03-30 17:17:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_journal`
--

CREATE TABLE `tbl_journal` (
  `journal_id` int(25) NOT NULL,
  `journal_no` varchar(50) NOT NULL,
  `posting_date` date NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `journal_type` varchar(50) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `approved_by` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_journal`
--

INSERT INTO `tbl_journal` (`journal_id`, `journal_no`, `posting_date`, `reference_no`, `journal_type`, `remarks`, `status`, `approved_by`, `created_by`, `created_at`) VALUES
(2, 'TEST', '2026-03-27', 'OR-29103SI', 'Cash Receipt', 'hehehehehe', 'Draft', 'heheheheheh', 'ADMIN-KYLE', '2026-03-27 20:54:26'),
(3, 'JE-2026-001', '2026-03-27', 'OR-29103SI', 'Cash Receipt', 'Collection of cash payment from client', 'Posted', 'Jovy S. Medina', 'ADMIN-KYLE', '2026-03-27 21:10:23'),
(4, 'tessttasdasd', '2026-03-30', 'OR-29103SI1231', 'Purchase', 'teasdasdasd', 'Draft', 'asdasdasdasdasd', 'ADMIN-KYLE', '2026-03-30 17:47:13'),
(5, 'JE-1231231231', '2026-05-19', 'cr', 'Cash Receipt', 'asdasdas', 'Draft', 'asdasda', 'ADMIN-KYLE', '2026-05-19 16:11:21'),
(6, 'JE-1231231231', '2026-05-19', 'cr', 'Cash Receipt', 'asdasdas', 'Draft', 'asdasda', 'ADMIN-KYLE', '2026-05-19 16:11:21'),
(7, 'JE-1231231231', '2026-05-19', 'cr', 'Cash Receipt', 'asdasdas', 'Draft', 'asdasda', 'ADMIN-KYLE', '2026-05-19 16:11:22'),
(8, 'sdasdasdasdasd', '2026-05-19', 'cr', 'Sales', 'asdasd', 'Draft', 'asdasda', 'ADMIN-KYLE', '2026-05-19 16:11:57'),
(9, 'sdasdasdasdasd', '2026-05-19', 'cr', 'Sales', 'asdasd', 'Draft', 'asdasda', 'ADMIN-KYLE', '2026-05-19 16:11:57'),
(10, 'sdasdasdasdasd', '2026-05-19', 'cr', 'Sales', 'asdasd', 'Draft', 'asdasda', 'ADMIN-KYLE', '2026-05-19 16:11:57'),
(11, 'JETEST2', '2026-05-19', 'CR', 'Purchase', 'ASDASDA', 'Draft', 'ASDASDA', 'ADMIN-KYLE', '2026-05-19 16:18:10'),
(12, 'JETEST2', '2026-05-19', 'CR', 'Purchase', 'ASDASDA', 'Draft', 'ASDASDA', 'ADMIN-KYLE', '2026-05-19 16:18:10'),
(13, 'JRN-202605-0009', '2026-05-19', 'CR', 'Purchase', 'ASDASDASD', 'Draft', '', 'ADMIN-KYLE', '2026-05-19 16:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_journal_details`
--

CREATE TABLE `tbl_journal_details` (
  `details_id` int(25) NOT NULL,
  `journal_id` int(25) NOT NULL,
  `account_code` varchar(50) NOT NULL,
  `account_name` varchar(50) NOT NULL,
  `debit_amount` decimal(15,2) NOT NULL,
  `credit_amount` decimal(15,2) NOT NULL,
  `description` varchar(100) NOT NULL,
  `cost_center` varchar(50) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_journal_details`
--

INSERT INTO `tbl_journal_details` (`details_id`, `journal_id`, `account_code`, `account_name`, `debit_amount`, `credit_amount`, `description`, `cost_center`, `created_by`, `created_at`) VALUES
(15, 3, '1001', 'Cash', 1000.00, 0.00, 'Cash received from client', 'CC-01', 'ADMIN-KYLE', '2026-03-27 21:10:23'),
(16, 3, '2001', 'Accounts Receivable', 0.00, 1000.00, 'Payment applied to invoice #', 'CC-01', 'ADMIN-KYLE', '2026-03-27 21:10:23'),
(17, 2, '1001', 'CASH', 1000.00, 0.00, 'TTEST', 'TES', 'ADMIN-KYLE', '2026-03-30 17:46:41'),
(18, 2, '2001', 'TEST', 0.00, 1000.00, 'TES', 'TEST', 'ADMIN-KYLE', '2026-03-30 17:46:41'),
(19, 2, '1040', 'Inventory', 1000.00, 0.00, 'test', 'tes', 'ADMIN-KYLE', '2026-03-30 17:46:41'),
(20, 2, '1030', 'Accounts Receivable', 0.00, 1000.00, 'test', 'ttest', 'ADMIN-KYLE', '2026-03-30 17:46:41'),
(23, 4, '1020', 'Cash in Bank', 1000.00, 0.00, 'test', 'test', 'ADMIN-KYLE', '2026-03-30 17:47:33'),
(24, 4, '1040', 'Inventory', 0.00, 1000.00, 'test', 'test', 'ADMIN-KYLE', '2026-03-30 17:47:33'),
(25, 4, '1030', 'Accounts Receivable', 222.00, 0.00, 'tets', 'ttest', 'ADMIN-KYLE', '2026-03-30 17:47:33'),
(26, 4, '1050', 'Prepaid Expenses', 0.00, 222.00, 'tes', 'tes', 'ADMIN-KYLE', '2026-03-30 17:47:33'),
(27, 5, '4010', 'Sales Revenue', 0.00, 5000.00, 'dasdasda', 'asdas', 'ADMIN-KYLE', '2026-05-19 16:11:21'),
(28, 6, '4010', 'Sales Revenue', 0.00, 5000.00, 'dasdasda', 'asdas', 'ADMIN-KYLE', '2026-05-19 16:11:21'),
(29, 7, '4010', 'Sales Revenue', 0.00, 5000.00, 'dasdasda', 'asdas', 'ADMIN-KYLE', '2026-05-19 16:11:22'),
(30, 8, '1030', 'Accounts Receivable', 1000.00, 0.00, '', '', 'ADMIN-KYLE', '2026-05-19 16:11:57'),
(31, 9, '1030', 'Accounts Receivable', 1000.00, 0.00, '', '', 'ADMIN-KYLE', '2026-05-19 16:11:57'),
(32, 10, '1030', 'Accounts Receivable', 1000.00, 0.00, '', '', 'ADMIN-KYLE', '2026-05-19 16:11:57'),
(33, 11, '2000', 'Current Liabilities', 50000.00, 0.00, 'ASDASD', 'CC-01', 'ADMIN-KYLE', '2026-05-19 16:18:10'),
(34, 12, '2000', 'Current Liabilities', 50000.00, 0.00, 'ASDASD', 'CC-01', 'ADMIN-KYLE', '2026-05-19 16:18:10'),
(36, 13, '2010', 'Accounts Payable', 0.00, 23000.00, 'SDASDA', 'CC-01', 'ADMIN-KYLE', '2026-05-19 16:21:18'),
(37, 13, '1530', 'Intangible Assets', 23000.00, 0.00, 'DASDAS', 'ASDASD', 'ADMIN-KYLE', '2026-05-19 16:21:18');

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
(13, 10, 'Personal Loan', 3000000.00, 15.00, 12, '2026-03-25', '2027-02-25', 'Pending', '1', 'ADMIN-KYLE', '2026-03-25 16:48:57'),
(14, 12, 'Personal Loan', 1000000.00, 10.00, 12, '2026-04-06', '2027-03-06', 'Pending', '2', 'ADMIN-KYLE', '2026-04-06 07:41:46'),
(15, 13, 'Personal Loan', 1000000.00, 10.00, 12, '2026-04-08', '2027-03-08', 'Pending', '2', 'ADMIN-KYLE', '2026-04-08 09:05:21'),
(16, 11, 'Personal Loan', 10000000.00, 10.00, 12, '2026-04-21', '2027-03-21', 'Pending', '1', 'ADMIN-KYLE', '2026-04-21 01:55:31'),
(17, 14, 'Auto Loan', 1000000.00, 10.00, 24, '2026-04-21', '2028-03-21', 'Pending', '1', 'ADMIN-KYLE', '2026-04-21 07:06:42'),
(18, 15, 'Home Loan', 1000000.00, 10.00, 12, '2026-05-19', '2027-05-19', 'Pending', '14', 'ADMIN-KYLE', '2026-05-19 14:38:15'),
(19, 17, 'Personal Loan', 1000000.00, 10.00, 12, '2026-05-21', '2027-05-21', 'Pending', '3', 'ADMIN-KYLE', '2026-05-21 14:55:48'),
(20, 19, 'Home Loan', 2000000.00, 15.00, 24, '2026-05-21', '2028-05-21', 'Pending', '1', 'ADMIN-KYLE', '2026-05-21 14:58:39'),
(21, 18, 'Auto Loan', 1000000.00, 10.00, 60, '2026-05-21', '2031-05-21', 'Pending', '1', 'ADMIN-KYLE', '2026-05-21 21:05:27');

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
(80, 13, 10, 2, '2026-04-25', 2766725.06, 34584.06, 236190.87, 270774.94, 'Paid', 2530534.19, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(81, 13, 10, 3, '2026-05-25', 2530534.19, 31631.68, 239143.26, 270774.94, 'unpaid', 2291390.93, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(82, 13, 10, 4, '2026-06-25', 2291390.93, 28642.39, 242132.55, 270774.94, 'unpaid', 2049258.38, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(83, 13, 10, 5, '2026-07-25', 2049258.38, 25615.73, 245159.21, 270774.94, 'unpaid', 1804099.17, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(84, 13, 10, 6, '2026-08-25', 1804099.17, 22551.24, 248223.70, 270774.94, 'unpaid', 1555875.47, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(85, 13, 10, 7, '2026-09-25', 1555875.47, 19448.44, 251326.49, 270774.94, 'unpaid', 1304548.98, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(86, 13, 10, 8, '2026-10-25', 1304548.98, 16306.86, 254468.07, 270774.94, 'unpaid', 1050080.91, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(87, 13, 10, 9, '2026-11-25', 1050080.91, 13126.01, 257648.93, 270774.94, 'unpaid', 792431.98, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(88, 13, 10, 10, '2026-12-25', 792431.98, 9905.40, 260869.54, 270774.94, 'unpaid', 531562.44, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(89, 13, 10, 11, '2027-01-25', 531562.44, 6644.53, 264130.41, 270774.94, 'unpaid', 267432.04, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(90, 13, 10, 12, '2027-02-25', 267432.04, 3342.90, 267432.04, 270774.94, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-03-25 08:48:57'),
(91, 14, 12, 1, '2026-04-06', 1000000.00, 8333.33, 79582.55, 87915.89, 'Paid', 920417.45, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(92, 14, 12, 2, '2026-05-06', 920417.45, 7670.15, 80245.74, 87915.89, 'unpaid', 840171.70, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(93, 14, 12, 3, '2026-06-06', 840171.70, 7001.43, 80914.46, 87915.89, 'unpaid', 759257.25, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(94, 14, 12, 4, '2026-07-06', 759257.25, 6327.14, 81588.74, 87915.89, 'unpaid', 677668.50, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(95, 14, 12, 5, '2026-08-06', 677668.50, 5647.24, 82268.65, 87915.89, 'unpaid', 595399.85, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(96, 14, 12, 6, '2026-09-06', 595399.85, 4961.67, 82954.22, 87915.89, 'unpaid', 512445.63, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(97, 14, 12, 7, '2026-10-06', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(98, 14, 12, 8, '2026-11-06', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(99, 14, 12, 9, '2026-12-06', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(100, 14, 12, 10, '2027-01-06', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(101, 14, 12, 11, '2027-02-06', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(102, 14, 12, 12, '2027-03-06', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-04-05 23:41:46'),
(103, 15, 13, 1, '2026-04-08', 1000000.00, 8333.33, 79582.55, 87915.89, 'Paid', 920417.45, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(104, 15, 13, 2, '2026-05-08', 920417.45, 7670.15, 80245.74, 87915.89, 'unpaid', 840171.70, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(105, 15, 13, 3, '2026-06-08', 840171.70, 7001.43, 80914.46, 87915.89, 'unpaid', 759257.25, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(106, 15, 13, 4, '2026-07-08', 759257.25, 6327.14, 81588.74, 87915.89, 'unpaid', 677668.50, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(107, 15, 13, 5, '2026-08-08', 677668.50, 5647.24, 82268.65, 87915.89, 'unpaid', 595399.85, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(108, 15, 13, 6, '2026-09-08', 595399.85, 4961.67, 82954.22, 87915.89, 'unpaid', 512445.63, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(109, 15, 13, 7, '2026-10-08', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(110, 15, 13, 8, '2026-11-08', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(111, 15, 13, 9, '2026-12-08', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(112, 15, 13, 10, '2027-01-08', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(113, 15, 13, 11, '2027-02-08', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(114, 15, 13, 12, '2027-03-08', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-04-08 01:05:21'),
(115, 16, 11, 1, '2026-04-21', 10000000.00, 83333.33, 795825.54, 879158.87, 'Paid', 9204174.46, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(116, 16, 11, 2, '2026-05-21', 9204174.46, 76701.45, 802457.42, 879158.87, 'Paid', 8401717.04, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(117, 16, 11, 3, '2026-06-21', 8401717.04, 70014.31, 809144.56, 879158.87, 'unpaid', 7592572.48, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(118, 16, 11, 4, '2026-07-21', 7592572.48, 63271.44, 815887.43, 879158.87, 'unpaid', 6776685.04, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(119, 16, 11, 5, '2026-08-21', 6776685.04, 56472.38, 822686.50, 879158.87, 'unpaid', 5953998.55, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(120, 16, 11, 6, '2026-09-21', 5953998.55, 49616.65, 829542.22, 879158.87, 'unpaid', 5124456.33, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(121, 16, 11, 7, '2026-10-21', 5124456.33, 42703.80, 836455.07, 879158.87, 'unpaid', 4288001.26, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(122, 16, 11, 8, '2026-11-21', 4288001.26, 35733.34, 843425.53, 879158.87, 'unpaid', 3444575.73, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(123, 16, 11, 9, '2026-12-21', 3444575.73, 28704.80, 850454.07, 879158.87, 'unpaid', 2594121.66, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(124, 16, 11, 10, '2027-01-21', 2594121.66, 21617.68, 857541.19, 879158.87, 'unpaid', 1736580.46, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(125, 16, 11, 11, '2027-02-21', 1736580.46, 14471.50, 864687.37, 879158.87, 'unpaid', 871893.10, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(126, 16, 11, 12, '2027-03-21', 871893.10, 7265.78, 871893.10, 879158.87, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-04-21 08:55:31'),
(127, 17, 14, 1, '2026-04-21', 1000000.00, 8333.33, 37811.59, 46144.93, 'Paid', 962188.41, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(128, 17, 14, 2, '2026-05-21', 962188.41, 8018.24, 38126.69, 46144.93, 'Paid', 924061.72, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(129, 17, 14, 3, '2026-06-21', 924061.72, 7700.51, 38444.41, 46144.93, 'Paid', 885617.31, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(130, 17, 14, 4, '2026-07-21', 885617.31, 7380.14, 38764.78, 46144.93, 'unpaid', 846852.52, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(131, 17, 14, 5, '2026-08-21', 846852.52, 7057.10, 39087.82, 46144.93, 'unpaid', 807764.70, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(132, 17, 14, 6, '2026-09-21', 807764.70, 6731.37, 39413.55, 46144.93, 'unpaid', 768351.15, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(133, 17, 14, 7, '2026-10-21', 768351.15, 6402.93, 39742.00, 46144.93, 'unpaid', 728609.15, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(134, 17, 14, 8, '2026-11-21', 728609.15, 6071.74, 40073.18, 46144.93, 'unpaid', 688535.96, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(135, 17, 14, 9, '2026-12-21', 688535.96, 5737.80, 40407.13, 46144.93, 'unpaid', 648128.84, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(136, 17, 14, 10, '2027-01-21', 648128.84, 5401.07, 40743.85, 46144.93, 'unpaid', 607384.98, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(137, 17, 14, 11, '2027-02-21', 607384.98, 5061.54, 41083.38, 46144.93, 'unpaid', 566301.60, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(138, 17, 14, 12, '2027-03-21', 566301.60, 4719.18, 41425.75, 46144.93, 'unpaid', 524875.85, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(139, 17, 14, 13, '2027-04-21', 524875.85, 4373.97, 41770.96, 46144.93, 'unpaid', 483104.89, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(140, 17, 14, 14, '2027-05-21', 483104.89, 4025.87, 42119.05, 46144.93, 'unpaid', 440985.84, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(141, 17, 14, 15, '2027-06-21', 440985.84, 3674.88, 42470.04, 46144.93, 'unpaid', 398515.80, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(142, 17, 14, 16, '2027-07-21', 398515.80, 3320.96, 42823.96, 46144.93, 'unpaid', 355691.83, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(143, 17, 14, 17, '2027-08-21', 355691.83, 2964.10, 43180.83, 46144.93, 'unpaid', 312511.01, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(144, 17, 14, 18, '2027-09-21', 312511.01, 2604.26, 43540.67, 46144.93, 'unpaid', 268970.34, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(145, 17, 14, 19, '2027-10-21', 268970.34, 2241.42, 43903.51, 46144.93, 'unpaid', 225066.83, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(146, 17, 14, 20, '2027-11-21', 225066.83, 1875.56, 44269.37, 46144.93, 'unpaid', 180797.46, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(147, 17, 14, 21, '2027-12-21', 180797.46, 1506.65, 44638.28, 46144.93, 'unpaid', 136159.18, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(148, 17, 14, 22, '2028-01-21', 136159.18, 1134.66, 45010.27, 46144.93, 'unpaid', 91148.92, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(149, 17, 14, 23, '2028-02-21', 91148.92, 759.57, 45385.35, 46144.93, 'unpaid', 45763.56, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(150, 17, 14, 24, '2028-03-21', 45763.56, 381.36, 45763.56, 46144.93, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-04-21 14:06:42'),
(151, 18, 15, 1, '2026-05-19', 1000000.00, 8333.33, 79582.55, 87915.89, 'Paid', 920417.45, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(152, 18, 15, 2, '2026-06-19', 920417.45, 7670.15, 80245.74, 87915.89, 'unpaid', 840171.70, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(153, 18, 15, 3, '2026-07-19', 840171.70, 7001.43, 80914.46, 87915.89, 'unpaid', 759257.25, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(154, 18, 15, 4, '2026-08-19', 759257.25, 6327.14, 81588.74, 87915.89, 'unpaid', 677668.50, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(155, 18, 15, 5, '2026-09-19', 677668.50, 5647.24, 82268.65, 87915.89, 'unpaid', 595399.85, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(156, 18, 15, 6, '2026-10-19', 595399.85, 4961.67, 82954.22, 87915.89, 'unpaid', 512445.63, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(157, 18, 15, 7, '2026-11-19', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(158, 18, 15, 8, '2026-12-19', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(159, 18, 15, 9, '2027-01-19', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(160, 18, 15, 10, '2027-02-19', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(161, 18, 15, 11, '2027-03-19', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(162, 18, 15, 12, '2027-04-19', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-05-19 06:38:15'),
(163, 19, 17, 1, '2026-05-21', 1000000.00, 8333.33, 79582.55, 87915.89, 'Paid', 920417.45, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(164, 19, 17, 2, '2026-06-21', 920417.45, 7670.15, 80245.74, 87915.89, 'Paid', 840171.70, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(165, 19, 17, 3, '2026-07-21', 840171.70, 7001.43, 80914.46, 87915.89, 'Paid', 759257.25, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(166, 19, 17, 4, '2026-08-21', 759257.25, 6327.14, 81588.74, 87915.89, 'Paid', 677668.50, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(167, 19, 17, 5, '2026-09-21', 677668.50, 5647.24, 82268.65, 87915.89, 'Paid', 595399.85, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(168, 19, 17, 6, '2026-10-21', 595399.85, 4961.67, 82954.22, 87915.89, 'Paid', 512445.63, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(169, 19, 17, 7, '2026-11-21', 512445.63, 4270.38, 83645.51, 87915.89, 'unpaid', 428800.13, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(170, 19, 17, 8, '2026-12-21', 428800.13, 3573.33, 84342.55, 87915.89, 'unpaid', 344457.57, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(171, 19, 17, 9, '2027-01-21', 344457.57, 2870.48, 85045.41, 87915.89, 'unpaid', 259412.17, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(172, 19, 17, 10, '2027-02-21', 259412.17, 2161.77, 85754.12, 87915.89, 'unpaid', 173658.05, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(173, 19, 17, 11, '2027-03-21', 173658.05, 1447.15, 86468.74, 87915.89, 'unpaid', 87189.31, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(174, 19, 17, 12, '2027-04-21', 87189.31, 726.58, 87189.31, 87915.89, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-05-21 06:55:48'),
(175, 20, 19, 1, '2026-05-21', 2000000.00, 25000.00, 71973.30, 96973.30, 'Paid', 1928026.70, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(176, 20, 19, 2, '2026-06-21', 1928026.70, 24100.33, 72872.96, 96973.30, 'Paid', 1855153.74, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(177, 20, 19, 3, '2026-07-21', 1855153.74, 23189.42, 73783.87, 96973.30, 'unpaid', 1781369.87, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(178, 20, 19, 4, '2026-08-21', 1781369.87, 22267.12, 74706.17, 96973.30, 'unpaid', 1706663.69, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(179, 20, 19, 5, '2026-09-21', 1706663.69, 21333.30, 75640.00, 96973.30, 'unpaid', 1631023.69, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(180, 20, 19, 6, '2026-10-21', 1631023.69, 20387.80, 76585.50, 96973.30, 'unpaid', 1554438.19, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(181, 20, 19, 7, '2026-11-21', 1554438.19, 19430.48, 77542.82, 96973.30, 'unpaid', 1476895.38, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(182, 20, 19, 8, '2026-12-21', 1476895.38, 18461.19, 78512.10, 96973.30, 'unpaid', 1398383.27, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(183, 20, 19, 9, '2027-01-21', 1398383.27, 17479.79, 79493.51, 96973.30, 'unpaid', 1318889.77, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(184, 20, 19, 10, '2027-02-21', 1318889.77, 16486.12, 80487.17, 96973.30, 'unpaid', 1238402.59, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(185, 20, 19, 11, '2027-03-21', 1238402.59, 15480.03, 81493.26, 96973.30, 'unpaid', 1156909.33, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(186, 20, 19, 12, '2027-04-21', 1156909.33, 14461.37, 82511.93, 96973.30, 'unpaid', 1074397.40, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(187, 20, 19, 13, '2027-05-21', 1074397.40, 13429.97, 83543.33, 96973.30, 'unpaid', 990854.07, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(188, 20, 19, 14, '2027-06-21', 990854.07, 12385.68, 84587.62, 96973.30, 'unpaid', 906266.45, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(189, 20, 19, 15, '2027-07-21', 906266.45, 11328.33, 85644.97, 96973.30, 'unpaid', 820621.49, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(190, 20, 19, 16, '2027-08-21', 820621.49, 10257.77, 86715.53, 96973.30, 'unpaid', 733905.96, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(191, 20, 19, 17, '2027-09-21', 733905.96, 9173.82, 87799.47, 96973.30, 'unpaid', 646106.49, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(192, 20, 19, 18, '2027-10-21', 646106.49, 8076.33, 88896.97, 96973.30, 'unpaid', 557209.52, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(193, 20, 19, 19, '2027-11-21', 557209.52, 6965.12, 90008.18, 96973.30, 'unpaid', 467201.34, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(194, 20, 19, 20, '2027-12-21', 467201.34, 5840.02, 91133.28, 96973.30, 'unpaid', 376068.07, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(195, 20, 19, 21, '2028-01-21', 376068.07, 4700.85, 92272.45, 96973.30, 'unpaid', 283795.62, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(196, 20, 19, 22, '2028-02-21', 283795.62, 3547.45, 93425.85, 96973.30, 'unpaid', 190369.77, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(197, 20, 19, 23, '2028-03-21', 190369.77, 2379.62, 94593.67, 96973.30, 'unpaid', 95776.09, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(198, 20, 19, 24, '2028-04-21', 95776.09, 1197.20, 95776.09, 96973.30, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-05-21 06:58:39'),
(199, 21, 18, 1, '2026-05-21', 1000000.00, 8333.33, 12913.71, 21247.04, 'Paid', 987086.29, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(200, 21, 18, 2, '2026-06-21', 987086.29, 8225.72, 13021.33, 21247.04, 'unpaid', 974064.96, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(201, 21, 18, 3, '2026-07-21', 974064.96, 8117.21, 13129.84, 21247.04, 'unpaid', 960935.13, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(202, 21, 18, 4, '2026-08-21', 960935.13, 8007.79, 13239.25, 21247.04, 'unpaid', 947695.87, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(203, 21, 18, 5, '2026-09-21', 947695.87, 7897.47, 13349.58, 21247.04, 'unpaid', 934346.30, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(204, 21, 18, 6, '2026-10-21', 934346.30, 7786.22, 13460.83, 21247.04, 'unpaid', 920885.47, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(205, 21, 18, 7, '2026-11-21', 920885.47, 7674.05, 13573.00, 21247.04, 'unpaid', 907312.47, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(206, 21, 18, 8, '2026-12-21', 907312.47, 7560.94, 13686.11, 21247.04, 'unpaid', 893626.36, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(207, 21, 18, 9, '2027-01-21', 893626.36, 7446.89, 13800.16, 21247.04, 'unpaid', 879826.20, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(208, 21, 18, 10, '2027-02-21', 879826.20, 7331.89, 13915.16, 21247.04, 'unpaid', 865911.05, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(209, 21, 18, 11, '2027-03-21', 865911.05, 7215.93, 14031.12, 21247.04, 'unpaid', 851879.93, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(210, 21, 18, 12, '2027-04-21', 851879.93, 7099.00, 14148.05, 21247.04, 'unpaid', 837731.88, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(211, 21, 18, 13, '2027-05-21', 837731.88, 6981.10, 14265.95, 21247.04, 'unpaid', 823465.93, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(212, 21, 18, 14, '2027-06-21', 823465.93, 6862.22, 14384.83, 21247.04, 'unpaid', 809081.11, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(213, 21, 18, 15, '2027-07-21', 809081.11, 6742.34, 14504.70, 21247.04, 'unpaid', 794576.40, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(214, 21, 18, 16, '2027-08-21', 794576.40, 6621.47, 14625.57, 21247.04, 'unpaid', 779950.83, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(215, 21, 18, 17, '2027-09-21', 779950.83, 6499.59, 14747.45, 21247.04, 'unpaid', 765203.37, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(216, 21, 18, 18, '2027-10-21', 765203.37, 6376.69, 14870.35, 21247.04, 'unpaid', 750333.02, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(217, 21, 18, 19, '2027-11-21', 750333.02, 6252.78, 14994.27, 21247.04, 'unpaid', 735338.76, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(218, 21, 18, 20, '2027-12-21', 735338.76, 6127.82, 15119.22, 21247.04, 'unpaid', 720219.53, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(219, 21, 18, 21, '2028-01-21', 720219.53, 6001.83, 15245.22, 21247.04, 'unpaid', 704974.32, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(220, 21, 18, 22, '2028-02-21', 704974.32, 5874.79, 15372.26, 21247.04, 'unpaid', 689602.06, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(221, 21, 18, 23, '2028-03-21', 689602.06, 5746.68, 15500.36, 21247.04, 'unpaid', 674101.70, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(222, 21, 18, 24, '2028-04-21', 674101.70, 5617.51, 15629.53, 21247.04, 'unpaid', 658472.17, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(223, 21, 18, 25, '2028-05-21', 658472.17, 5487.27, 15759.78, 21247.04, 'unpaid', 642712.39, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(224, 21, 18, 26, '2028-06-21', 642712.39, 5355.94, 15891.11, 21247.04, 'unpaid', 626821.28, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(225, 21, 18, 27, '2028-07-21', 626821.28, 5223.51, 16023.53, 21247.04, 'unpaid', 610797.75, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(226, 21, 18, 28, '2028-08-21', 610797.75, 5089.98, 16157.06, 21247.04, 'unpaid', 594640.69, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(227, 21, 18, 29, '2028-09-21', 594640.69, 4955.34, 16291.71, 21247.04, 'unpaid', 578348.98, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(228, 21, 18, 30, '2028-10-21', 578348.98, 4819.57, 16427.47, 21247.04, 'unpaid', 561921.51, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(229, 21, 18, 31, '2028-11-21', 561921.51, 4682.68, 16564.37, 21247.04, 'unpaid', 545357.14, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(230, 21, 18, 32, '2028-12-21', 545357.14, 4544.64, 16702.40, 21247.04, 'unpaid', 528654.74, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(231, 21, 18, 33, '2029-01-21', 528654.74, 4405.46, 16841.59, 21247.04, 'unpaid', 511813.15, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(232, 21, 18, 34, '2029-02-21', 511813.15, 4265.11, 16981.94, 21247.04, 'unpaid', 494831.22, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(233, 21, 18, 35, '2029-03-21', 494831.22, 4123.59, 17123.45, 21247.04, 'unpaid', 477707.77, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(234, 21, 18, 36, '2029-04-21', 477707.77, 3980.90, 17266.15, 21247.04, 'unpaid', 460441.62, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(235, 21, 18, 37, '2029-05-21', 460441.62, 3837.01, 17410.03, 21247.04, 'unpaid', 443031.59, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(236, 21, 18, 38, '2029-06-21', 443031.59, 3691.93, 17555.11, 21247.04, 'unpaid', 425476.48, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(237, 21, 18, 39, '2029-07-21', 425476.48, 3545.64, 17701.41, 21247.04, 'unpaid', 407775.07, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(238, 21, 18, 40, '2029-08-21', 407775.07, 3398.13, 17848.92, 21247.04, 'unpaid', 389926.15, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(239, 21, 18, 41, '2029-09-21', 389926.15, 3249.38, 17997.66, 21247.04, 'unpaid', 371928.49, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(240, 21, 18, 42, '2029-10-21', 371928.49, 3099.40, 18147.64, 21247.04, 'unpaid', 353780.85, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(241, 21, 18, 43, '2029-11-21', 353780.85, 2948.17, 18298.87, 21247.04, 'unpaid', 335481.98, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(242, 21, 18, 44, '2029-12-21', 335481.98, 2795.68, 18451.36, 21247.04, 'unpaid', 317030.62, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(243, 21, 18, 45, '2030-01-21', 317030.62, 2641.92, 18605.12, 21247.04, 'unpaid', 298425.49, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(244, 21, 18, 46, '2030-02-21', 298425.49, 2486.88, 18760.17, 21247.04, 'unpaid', 279665.33, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(245, 21, 18, 47, '2030-03-21', 279665.33, 2330.54, 18916.50, 21247.04, 'unpaid', 260748.83, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(246, 21, 18, 48, '2030-04-21', 260748.83, 2172.91, 19074.14, 21247.04, 'unpaid', 241674.69, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(247, 21, 18, 49, '2030-05-21', 241674.69, 2013.96, 19233.09, 21247.04, 'unpaid', 222441.60, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(248, 21, 18, 50, '2030-06-21', 222441.60, 1853.68, 19393.36, 21247.04, 'unpaid', 203048.24, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(249, 21, 18, 51, '2030-07-21', 203048.24, 1692.07, 19554.98, 21247.04, 'unpaid', 183493.26, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(250, 21, 18, 52, '2030-08-21', 183493.26, 1529.11, 19717.93, 21247.04, 'unpaid', 163775.33, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(251, 21, 18, 53, '2030-09-21', 163775.33, 1364.79, 19882.25, 21247.04, 'unpaid', 143893.07, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(252, 21, 18, 54, '2030-10-21', 143893.07, 1199.11, 20047.94, 21247.04, 'unpaid', 123845.14, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(253, 21, 18, 55, '2030-11-21', 123845.14, 1032.04, 20215.00, 21247.04, 'unpaid', 103630.14, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(254, 21, 18, 56, '2030-12-21', 103630.14, 863.58, 20383.46, 21247.04, 'unpaid', 83246.68, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(255, 21, 18, 57, '2031-01-21', 83246.68, 693.72, 20553.32, 21247.04, 'unpaid', 62693.35, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(256, 21, 18, 58, '2031-02-21', 62693.35, 522.44, 20724.60, 21247.04, 'unpaid', 41968.75, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(257, 21, 18, 59, '2031-03-21', 41968.75, 349.74, 20897.31, 21247.04, 'unpaid', 21071.45, 'ADMIN-KYLE', '2026-05-21 13:05:27'),
(258, 21, 18, 60, '2031-04-21', 21071.45, 175.60, 21071.45, 21247.04, 'unpaid', 0.00, 'ADMIN-KYLE', '2026-05-21 13:05:27');

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
(9, 13, 10, 37500.00, 233274.94, 270774.94, '2026-03-25', 'ADMIN-KYLE', '2026-03-25 16:53:49'),
(10, 13, 10, 34584.06, 236190.87, 270774.94, '2026-04-25', 'ADMIN-KYLE', '2026-03-27 18:32:40'),
(11, 14, 12, 8333.33, 79582.55, 87915.89, '2026-04-06', 'ADMIN-KYLE', '2026-04-06 07:42:14'),
(12, 15, 13, 8333.33, 79582.55, 87915.89, '2026-04-08', 'ADMIN-KYLE', '2026-04-08 09:06:28'),
(13, 16, 11, 83333.33, 795825.54, 879158.87, '2026-04-21', 'ADMIN-KYLE', '2026-04-21 01:55:52'),
(14, 16, 11, 76701.45, 802457.42, 879158.87, '2026-05-21', 'ADMIN-KYLE', '2026-04-21 01:55:59'),
(15, 17, 14, 8333.33, 37811.59, 46144.93, '2026-04-21', 'ADMIN-KYLE', '2026-04-21 07:07:24'),
(16, 17, 14, 8018.24, 38126.69, 46144.93, '2026-05-21', 'ADMIN-KYLE', '2026-04-21 07:24:07'),
(17, 17, 14, 7700.51, 38444.41, 46144.93, '2026-06-21', 'ADMIN-KYLE', '2026-04-21 07:24:35'),
(18, 18, 15, 8333.33, 79582.55, 87915.89, '2026-05-19', 'ADMIN-KYLE', '2026-05-19 14:55:38'),
(19, 19, 17, 8333.33, 79582.55, 87915.89, '2026-05-21', 'ADMIN-KYLE', '2026-05-21 14:56:12'),
(20, 20, 19, 25000.00, 71973.30, 96973.30, '2026-05-21', 'ADMIN-KYLE', '2026-05-21 14:58:55'),
(21, 19, 17, 7670.15, 80245.74, 87915.89, '2026-06-21', 'ADMIN-KYLE', '2026-05-21 14:59:06'),
(22, 19, 17, 7001.43, 80914.46, 87915.89, '2026-07-21', 'ADMIN-KYLE', '2026-05-21 14:59:30'),
(23, 19, 17, 6327.14, 81588.74, 87915.89, '2026-08-21', 'ADMIN-KYLE', '2026-05-21 15:01:00'),
(24, 19, 17, 5647.24, 82268.65, 87915.89, '2026-09-21', 'ADMIN-KYLE', '2026-05-21 15:03:07'),
(25, 19, 17, 4961.67, 82954.22, 87915.89, '2026-10-21', 'ADMIN-KYLE', '2026-05-21 15:09:45'),
(26, 20, 19, 24100.33, 72872.96, 96973.30, '2026-06-21', 'ADMIN-KYLE', '2026-05-21 15:14:13'),
(27, 21, 18, 8333.33, 12913.71, 21247.04, '2026-05-21', 'ADMIN-KYLE', '2026-05-21 21:05:59');

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
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced') DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `tin` varchar(50) DEFAULT NULL,
  `gsis_number` varchar(50) DEFAULT NULL,
  `permanent_street` varchar(100) DEFAULT NULL,
  `permanent_barangay` varchar(50) DEFAULT NULL,
  `permanent_city` varchar(50) DEFAULT NULL,
  `permanent_province` varchar(50) DEFAULT NULL,
  `permanent_zip` varchar(10) DEFAULT NULL,
  `present_street` varchar(100) DEFAULT NULL,
  `present_barangay` varchar(50) DEFAULT NULL,
  `present_city` varchar(50) DEFAULT NULL,
  `present_province` varchar(50) DEFAULT NULL,
  `present_zip` varchar(10) DEFAULT NULL,
  `home_phone` varchar(50) DEFAULT NULL,
  `office_phone` varchar(50) DEFAULT NULL,
  `department_agency` enum('DOST-FNRI','DOST-ITDI') DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `salary_grade` varchar(10) DEFAULT NULL,
  `beneficiary1_name` varchar(100) DEFAULT NULL,
  `beneficiary1_address` varchar(200) DEFAULT NULL,
  `beneficiary1_contact` varchar(50) DEFAULT NULL,
  `beneficiary1_relationship` varchar(50) DEFAULT NULL,
  `beneficiary2_name` varchar(100) DEFAULT NULL,
  `beneficiary2_address` varchar(200) DEFAULT NULL,
  `beneficiary2_contact` varchar(50) DEFAULT NULL,
  `beneficiary2_relationship` varchar(50) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('member','officer','admin') NOT NULL DEFAULT 'member',
  `hash_password` varchar(100) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_photo_path` varchar(500) DEFAULT NULL COMMENT 'Profile photo file path',
  `profile_photo_name` varchar(255) DEFAULT NULL COMMENT 'Original profile photo name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_members`
--

INSERT INTO `tbl_members` (`member_id`, `member_no`, `first_name`, `last_name`, `middle_name`, `date_of_birth`, `place_of_birth`, `age`, `civil_status`, `gender`, `tin`, `gsis_number`, `permanent_street`, `permanent_barangay`, `permanent_city`, `permanent_province`, `permanent_zip`, `present_street`, `present_barangay`, `present_city`, `present_province`, `present_zip`, `home_phone`, `office_phone`, `department_agency`, `position`, `salary_grade`, `beneficiary1_name`, `beneficiary1_address`, `beneficiary1_contact`, `beneficiary1_relationship`, `beneficiary2_name`, `beneficiary2_address`, `beneficiary2_contact`, `beneficiary2_relationship`, `address`, `contact_number`, `email`, `username`, `password`, `role`, `hash_password`, `created_by`, `created_at`, `profile_photo_path`, `profile_photo_name`) VALUES
(1, 12301293, 'KYLE ANDRAEEE', 'ALIÑO', 'POSADAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123 address hehe caloocan city rararrarasdasdasdasda', '09158018602', 'kylealino@gmail.com', 'ADMIN-KYLE', '1234567', 'member', 'e13efc991a9bf44bbb4da87cdbb725240184585ccaf270523170e008cf2a3b85f45f86c3da647f69780fb9e971caf5437b3d', 'ADMIN-KYLE', '2026-03-22 10:00:14', 'uploads/profile_photos/profile_1_20260530_155026_9e587f0f.jpg', 'd117f1b2-82c3-4a98-b0f1-2480bacd0700.jpg'),
(2, 120312031, 'JAMIE', 'CRUZ', 'SANTIAGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'adsadasdasda', '192301231', 'jamiecruz@gmail.com', '', '', 'member', '', '', '2026-03-22 10:07:50', NULL, NULL),
(3, 2147483647, 'BRYAN', 'ALINO', 'POSADS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'adsadasdasdaasdasdasdasdasdasdasda', '1231231', 'bryan@gmail.com', '', '', 'member', '', '', '2026-03-22 10:08:02', NULL, NULL),
(4, 120301231, 'lee', 'alino', 'posasd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'asdasdas', '1203120', 'lee@gmail.com', '', '', 'member', '', '', '2026-03-22 10:09:18', NULL, NULL),
(5, 2147483647, 'JOVY ', 'MEDINA', 'S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123 test address taguig city', '109513266', 'jovymedina@gmail.com', 'ADMIN-JOVY', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-23 09:47:56', NULL, NULL),
(6, 2147483647, 'ALEX', 'HABIG', 'XANDER', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123 ilang ilang st baesa caloocan city', '910231', 'alexhabig@gmail.com', 'ADMIN-ALEX', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-23 10:57:23', NULL, NULL),
(7, 232565163, 'JEN', 'CRUZ', 'SAIRA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123 ilang ilang st. baesa caloocan city', '0920301301', 'jencruz@gmail.com', 'TEST-JEN', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:37:57', NULL, NULL),
(8, 2147483647, 'JEBOY', 'SANTIAGO', 'CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '237 A. Mabini St', '09182930120', 'jeboy@gmail.com', 'TEST-JEBOY', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:38:42', NULL, NULL),
(9, 232656323, 'MICHELLE', 'SANTIAGO', 'CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123 test address quezon city', '0910230102', 'michelle@gmail.com', 'TEST-MICHELLE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:39:20', NULL, NULL),
(10, 2147483647, 'ANDRAE', 'POSADAS', 'TUWAIA', '2007-06-12', 'CALOOCAN CITY', 18, '', '', '02934203', '9234920', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '09158018602', 'andrae@gmail.com', 'TEST-ANDRAE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-03-25 16:42:03', NULL, NULL),
(11, 1231231, 'GOMEZ', 'JUAN', 'DELIANO', '1999-01-05', 'CALOOCAN CITY', 27, 'Single', 'Male', '1231', '12312', 'asdas', 'dasd', 'asdasd', 'asdasd', '1111', 'asdasd', 'asdasd', 'adasda', 'asdasd', '1001', '123123', '123123', 'DOST-FNRI', 'Administrative', '23', 'adasd', 'asdasd', '12312', 'asdasda', 'dasdasdas', 'asdas', '12312', 'asdasd', '', '123123123', 'kylealino@gmail.com', 'ADMIN-KYLE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-04-03 20:32:12', NULL, NULL),
(12, 12310230, 'JERVEY', 'POSADAS', 'SOLANO', '2003-10-05', 'CALOOCAN CITY', 22, 'Married', 'Male', '9128319', '012031023', '203', 'BRGY 158', 'CALOOCAN CITY', 'MINDORO', '2003', '', '', '', '', '', '', '', 'DOST-FNRI', 'PTS III', '23', '', '', '', '', '', '', '', '', '', '0912094902', 'jervy@gmail.com', 'FNRI-JERVEY', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-04-06 07:40:45', NULL, NULL),
(13, 1920310, 'ALEXANDER', 'PRADO', 'GREAY', '1999-10-04', 'TAGUIG', 26, 'Married', 'Male', '0192301', '01230910', '203', '77', 'TAGUIG', 'NA', '2001', '203', '77', 'TAGUIG ', 'NA', '2001', '0192301', '1023010', 'DOST-FNRI', 'PTS I', '23', 'ALECK PRADO JR', '77 TAGUIG CITY', '019231013', 'SON', '', '', '', '', '', '0912031013', 'alec@gmail.com', 'MM-ALECK', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-04-08 09:03:32', NULL, NULL),
(14, 1203102310, 'SEAN', 'SUMAGAYSAY', 'TEST', '1999-10-04', 'CALOOCAN CITY', 26, '', '', '012301203', '012030120', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0912301231', 'SEAN@GMAIL.COM', 'TEST-SEAN', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-04-21 07:01:25', NULL, NULL),
(15, 2147483647, 'test', 'test', 'test', '1999-02-02', 'CALOOCAN CITY', 27, 'Single', '', '12312312', '31231231', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '09158018602', 'kylealino@msda.com', 'rara-test', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-05-19 14:13:58', NULL, NULL),
(16, 2147483647, 'TESTING TWO', 'ACCOUNT', 'ONE', '1999-10-04', 'CALOOCAN CITY', 26, 'Single', 'Male', '912312301', '012031231', '', '', '', '', '', '', '', '', '', '', '', '', 'DOST-FNRI', '', '', '', '', '', '', '', '', '', '', '', '09128819920', 'testaccountone@gmail.com', 'TESTACCOUNT-1', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-05-21 14:51:41', NULL, NULL),
(17, 231982301, 'TESTING ONE', 'ACCOUNT', 'TWO', '1999-10-04', 'CALOOCAN CITY', 26, 'Married', 'Male', '10293910', '1231231', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '09123193921', 'testaccounttwo@gmail.com', 'TESTACCOUNTTWO-KYLE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-05-21 14:52:37', NULL, NULL),
(18, 1231203120, 'TESTING THREE', 'ACCOUNT', 'TEST', '1999-10-04', 'CAL', 26, 'Single', 'Male', '124123123', '1231231', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0929391923', 'testaccountthree@gmail.com', 'TESTACCOUNTTHREE-KYLE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-05-21 14:54:51', NULL, NULL),
(19, 88521003, 'MO', 'HAWAK', 'ANG BEAT', '1999-10-04', 'CALOOCAN CITY', 26, 'Married', 'Male', '12319823', '1092831', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '029301293', 'kylealinosda@gmail.com', 'HAWAKBEAT-KYLE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-05-21 14:57:35', NULL, NULL),
(20, 1111111, 'TESTKYLE1313131ASDASD', 'ASDASDASD1231231', 'ASDASD123131', '1931-07-24', 'CALOOCAN CITY', 94, 'Single', 'Male', '12312', '31231', '', '', '', '', '', '', '', '', '', '', '', '', 'DOST-ITDI', 'Administrative', '23', '', '', '', '', '', '', '', '', '', '12312312', 'kylealino@gmail.com', 'ADMIN-KYLE', '123456', 'member', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc5', 'ADMIN-KYLE', '2026-05-30 14:02:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_documents`
--

CREATE TABLE `tbl_member_documents` (
  `doc_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL COMMENT 'gov_id, proof_of_group, id_photo, tin_gsis_proof, signed_membership, proof_of_income, bank_statement, collateral, salary_deduction_auth, loan_purpose_declaration',
  `document_name` varchar(255) NOT NULL COMMENT 'Original file name',
  `document_path` varchar(500) NOT NULL COMMENT 'File path in server',
  `file_size` int(11) DEFAULT NULL COMMENT 'File size in bytes',
  `file_type` varchar(50) DEFAULT NULL COMMENT 'MIME type: pdf, jpg, png, etc',
  `uploaded_by` int(11) DEFAULT NULL COMMENT 'User ID who uploaded the document',
  `upload_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('active','archived','deleted') DEFAULT 'active',
  `is_profile_photo` tinyint(1) DEFAULT 0 COMMENT '1 for profile photo, 0 for other documents'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_member_documents`
--

INSERT INTO `tbl_member_documents` (`doc_id`, `member_id`, `document_type`, `document_name`, `document_path`, `file_size`, `file_type`, `uploaded_by`, `upload_date`, `updated_date`, `status`, `is_profile_photo`) VALUES
(1, 11, 'gov_id', 'd117f1b2-82c3-4a98-b0f1-2480bacd0700.jpg', 'uploads/documents/11/gov_id_20260530_152411_553bd07a.jpg', 178771, 'jpg', 0, '2026-05-30 15:24:11', NULL, 'active', 0),
(2, 11, 'tin_gsis_proof', 'a00d93ea-30af-4488-a988-0d4c459ec64b.jpg', 'uploads/documents/11/tin_gsis_proof_20260530_152503_387a6ae4.jpg', 185677, 'jpg', 0, '2026-05-30 15:25:03', NULL, 'active', 0),
(3, 11, 'proof_of_group', 'a00d93ea-30af-4488-a988-0d4c459ec64b.jpg', 'uploads/documents/11/proof_of_group_20260530_153225_f8979342.jpg', 185677, 'jpg', 0, '2026-05-30 15:32:08', '2026-05-30 15:32:25', 'active', 0),
(4, 11, 'id_photo', 'c1ecc6b9-ba19-42f3-8904-cdd967a4511d.jpg', 'uploads/documents/11/id_photo_20260530_153249_4cb53f69.jpg', 176021, 'jpg', 0, '2026-05-30 15:32:49', NULL, 'active', 0),
(5, 20, 'id_photo', '1x1.png', 'uploads/documents/20/id_photo_20260530_155905_3a851a36.png', 168297, 'png', 0, '2026-05-30 15:56:01', '2026-05-30 15:59:05', 'active', 0),
(6, 20, 'proof_of_income', '61064b7f-7f98-4ce8-882c-a0fa5868b7e8.jpg', 'uploads/documents/20/proof_of_income_20260602_155654_a6b181a9.jpg', 184864, 'jpg', 0, '2026-06-02 15:56:54', NULL, 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_permissions`
--

CREATE TABLE `tbl_member_permissions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_save` tinyint(1) DEFAULT 0,
  `can_update` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_member_permissions`
--

INSERT INTO `tbl_member_permissions` (`id`, `member_id`, `module_name`, `can_view`, `can_save`, `can_update`, `created_at`, `updated_at`) VALUES
(9, 10, 'account_settings', 1, 1, 1, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(10, 10, 'list_members', 0, 0, 0, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(11, 10, 'loan_availment', 1, 1, 1, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(12, 10, 'loan_profile', 1, 1, 1, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(13, 10, 'journal_entry', 0, 0, 0, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(14, 10, 'subsidiary_ledger', 0, 0, 0, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(15, 10, 'chart_of_accounts', 0, 0, 0, '2026-04-03 06:15:11', '2026-04-03 06:15:11'),
(16, 10, 'financial_reports', 0, 0, 0, '2026-04-03 06:15:11', '2026-04-03 06:15:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `myua_user`
--
ALTER TABLE `myua_user`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `tbl_coa`
--
ALTER TABLE `tbl_coa`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_code` (`account_code`);

--
-- Indexes for table `tbl_journal`
--
ALTER TABLE `tbl_journal`
  ADD PRIMARY KEY (`journal_id`);

--
-- Indexes for table `tbl_journal_details`
--
ALTER TABLE `tbl_journal_details`
  ADD PRIMARY KEY (`details_id`);

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
-- Indexes for table `tbl_member_documents`
--
ALTER TABLE `tbl_member_documents`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `idx_member_id` (`member_id`),
  ADD KEY `idx_document_type` (`document_type`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `tbl_member_permissions`
--
ALTER TABLE `tbl_member_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member_module` (`member_id`,`module_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `myua_user`
--
ALTER TABLE `myua_user`
  MODIFY `recid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_coa`
--
ALTER TABLE `tbl_coa`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tbl_journal`
--
ALTER TABLE `tbl_journal`
  MODIFY `journal_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_journal_details`
--
ALTER TABLE `tbl_journal_details`
  MODIFY `details_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_loans`
--
ALTER TABLE `tbl_loans`
  MODIFY `loan_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_loans_ammortization`
--
ALTER TABLE `tbl_loans_ammortization`
  MODIFY `ammortization_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT for table `tbl_loans_payment`
--
ALTER TABLE `tbl_loans_payment`
  MODIFY `payment_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_members`
--
ALTER TABLE `tbl_members`
  MODIFY `member_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_member_documents`
--
ALTER TABLE `tbl_member_documents`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_member_permissions`
--
ALTER TABLE `tbl_member_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_member_permissions`
--
ALTER TABLE `tbl_member_permissions`
  ADD CONSTRAINT `tbl_member_permissions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `tbl_members` (`member_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
