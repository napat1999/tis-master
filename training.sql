-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2021 at 10:55 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `training`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `courseID` bigint(20) UNSIGNED NOT NULL,
  `coursemasterID` smallint(6) DEFAULT NULL,
  `coursegen` smallint(6) DEFAULT NULL,
  `nameOfficial` varchar(150) NOT NULL,
  `nameMarketing` varchar(150) DEFAULT NULL,
  `schedule` varchar(150) DEFAULT NULL,
  `dateApplyBegin` date DEFAULT NULL,
  `dateApplyEnd` date DEFAULT NULL,
  `minuteTrain` smallint(6) DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `requirement` varchar(150) DEFAULT NULL,
  `courseRemark` varchar(150) DEFAULT NULL,
  `status` smallint(6) DEFAULT 0,
  `approxstudent` smallint(6) DEFAULT NULL,
  `approxhead` smallint(6) DEFAULT NULL,
  `approxtotal` int(11) DEFAULT NULL,
  `budget` smallint(6) DEFAULT NULL,
  `siteid` smallint(6) DEFAULT NULL,
  `trainerid` varchar(20) DEFAULT NULL,
  `tagList` varchar(255) DEFAULT NULL,
  `createby` varchar(10) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updateby` varchar(10) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseID`, `coursemasterID`, `coursegen`, `nameOfficial`, `nameMarketing`, `schedule`, `dateApplyBegin`, `dateApplyEnd`, `minuteTrain`, `objective`, `content`, `requirement`, `courseRemark`, `status`, `approxstudent`, `approxhead`, `approxtotal`, `budget`, `siteid`, `trainerid`, `tagList`, `createby`, `createdate`, `updateby`, `lastupdate`) VALUES
(1222, 1221, 1, 'it-1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '1339', NULL, NULL, '2021-06-18 09:26:24', NULL, '2021-06-18 09:26:24'),
(1223, 1221, 1, 'it-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '1339', NULL, NULL, '2021-06-18 09:33:06', NULL, '2021-06-18 09:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `courseallocate`
--

CREATE TABLE `courseallocate` (
  `allocateID` bigint(20) UNSIGNED NOT NULL,
  `courseID` smallint(6) DEFAULT NULL,
  `thai_name` varchar(100) NOT NULL,
  `employeeNo` varchar(10) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `allocateQuota` smallint(6) DEFAULT NULL,
  `allocateAssign` smallint(6) DEFAULT NULL,
  `allocateLeft` smallint(6) DEFAULT NULL,
  `allocateUsed` smallint(6) DEFAULT NULL,
  `assignBy` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `courselog`
--

CREATE TABLE `courselog` (
  `logID` bigint(20) UNSIGNED NOT NULL,
  `courseID` smallint(6) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `updateby` varchar(10) DEFAULT NULL,
  `logupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coursemaster`
--

CREATE TABLE `coursemaster` (
  `coursemasterID` bigint(20) UNSIGNED NOT NULL,
  `coursecodeID` smallint(6) DEFAULT NULL,
  `courseLevel` smallint(6) DEFAULT NULL,
  `courseNumber` smallint(6) DEFAULT NULL,
  `courseSequence` smallint(6) DEFAULT NULL,
  `nameOfficial` varchar(150) NOT NULL,
  `nameMarketing` varchar(150) DEFAULT NULL,
  `schedule` varchar(150) DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `requirement` varchar(150) DEFAULT NULL,
  `courseRemark` varchar(150) DEFAULT NULL,
  `approxstudent` smallint(6) DEFAULT NULL,
  `approxhead` smallint(6) DEFAULT NULL,
  `approxtotal` int(11) DEFAULT NULL,
  `trainerid` varchar(20) DEFAULT NULL,
  `tagList` varchar(255) DEFAULT NULL,
  `createby` varchar(10) DEFAULT NULL,
  `updateby` varchar(10) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coursemaster`
--

INSERT INTO `coursemaster` (`coursemasterID`, `coursecodeID`, `courseLevel`, `courseNumber`, `courseSequence`, `nameOfficial`, `nameMarketing`, `schedule`, `objective`, `content`, `requirement`, `courseRemark`, `approxstudent`, `approxhead`, `approxtotal`, `trainerid`, `tagList`, `createby`, `updateby`, `lastupdate`) VALUES
(1221, 12, 2, 21, 1, 'it', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1339', NULL, NULL, NULL, '2021-06-18 09:25:49');

-- --------------------------------------------------------

--
-- Table structure for table `courseschedule`
--

CREATE TABLE `courseschedule` (
  `scheduleID` bigint(20) UNSIGNED NOT NULL,
  `courseID` smallint(6) DEFAULT NULL,
  `dateBegin` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateEnd` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `roundmins` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coursestudent`
--

CREATE TABLE `coursestudent` (
  `studentID` bigint(20) UNSIGNED NOT NULL,
  `courseID` smallint(6) DEFAULT NULL,
  `th_initial` varchar(10) DEFAULT NULL,
  `thai_name` varchar(100) NOT NULL,
  `employeeNo` varchar(10) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `personal_id` varchar(13) DEFAULT NULL,
  `status` smallint(6) DEFAULT 0,
  `studentRemark` varchar(150) DEFAULT NULL,
  `assignBy` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paramcode`
--

CREATE TABLE `paramcode` (
  `codeID` bigint(20) UNSIGNED NOT NULL,
  `codeName` varchar(3) NOT NULL,
  `codeDescription` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paramprovince`
--

CREATE TABLE `paramprovince` (
  `provinceID` bigint(20) UNSIGNED NOT NULL,
  `provinceName` varchar(60) DEFAULT NULL,
  `RO` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paramprovince`
--

INSERT INTO `paramprovince` (`provinceID`, `provinceName`, `RO`) VALUES
(123, 'A', 1);

-- --------------------------------------------------------

--
-- Table structure for table `paramtag`
--

CREATE TABLE `paramtag` (
  `tagID` bigint(20) UNSIGNED NOT NULL,
  `tagName` varchar(20) NOT NULL,
  `tagDescription` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `studentlog`
--

CREATE TABLE `studentlog` (
  `logID` bigint(20) UNSIGNED NOT NULL,
  `courseID` smallint(6) DEFAULT NULL,
  `studentID` smallint(6) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `updateby` varchar(10) DEFAULT NULL,
  `logupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tisusers`
--

CREATE TABLE `tisusers` (
  `userID` bigint(20) UNSIGNED NOT NULL,
  `th_initial` varchar(10) NOT NULL,
  `thai_name` varchar(100) NOT NULL,
  `employeeNo` varchar(10) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `workplace` varchar(30) DEFAULT NULL,
  `userro` varchar(2) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `userRemark` varchar(150) DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT 0,
  `istraininghq` tinyint(1) DEFAULT 0,
  `istrainingro` tinyint(1) DEFAULT 0,
  `iscoordinator` tinyint(1) DEFAULT 0,
  `createby` varchar(10) DEFAULT NULL,
  `updateby` varchar(10) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tisusers`
--

INSERT INTO `tisusers` (`userID`, `th_initial`, `thai_name`, `employeeNo`, `position`, `department`, `company`, `section`, `division`, `workplace`, `userro`, `telephone`, `email`, `userRemark`, `isadmin`, `istraininghq`, `istrainingro`, `iscoordinator`, `createby`, `updateby`, `lastupdate`) VALUES
(1883, 'นาย', 'แนพ', '1883', 'HR', 'HRD', 'Jas', 'RO8', 'RO8', 'office', '8', '0987654321', 'nap@jasmine.com', NULL, 0, 0, 0, 0, NULL, NULL, '2021-05-31 09:36:52'),
(60001, 'นาย', 'รพ', 'RO1637', 'HR', 'HRD', 'Jas', 'RO8', 'RO8', 'office', '8', '0987654321', 'rop@gmail.com', NULL, 0, 0, 0, 0, NULL, NULL, '2021-05-31 09:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `trainerID` bigint(20) UNSIGNED NOT NULL,
  `th_initial` varchar(10) DEFAULT NULL,
  `thai_name` varchar(100) NOT NULL,
  `name_en` text DEFAULT NULL,
  `lastname_en` text DEFAULT NULL,
  `employeeNo` varchar(10) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `workplace` varchar(30) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `studyinfo` text DEFAULT NULL,
  `workinfo` text DEFAULT NULL,
  `traininfo` text DEFAULT NULL,
  `trainerRemark` varchar(150) DEFAULT NULL,
  `createby` varchar(10) DEFAULT NULL,
  `updateby` varchar(10) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagepath` longtext DEFAULT NULL,
  `expends` text DEFAULT NULL,
  `spe_courses` text DEFAULT NULL,
  `course_em` text DEFAULT NULL,
  `gen` int(11) DEFAULT NULL,
  `year_train` text DEFAULT NULL,
  `trainer_type` varchar(1) DEFAULT NULL,
  `trainer_pict` text DEFAULT NULL,
  `contact_p` text DEFAULT NULL,
  `contact_tel` text DEFAULT NULL,
  `contact_email` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`trainerID`, `th_initial`, `thai_name`, `name_en`, `lastname_en`, `employeeNo`, `position`, `department`, `company`, `section`, `division`, `workplace`, `telephone`, `email`, `studyinfo`, `workinfo`, `traininfo`, `trainerRemark`, `createby`, `updateby`, `lastupdate`, `imagepath`, `expends`, `spe_courses`, `course_em`, `gen`, `year_train`, `trainer_type`, `trainer_pict`, `contact_p`, `contact_tel`, `contact_email`) VALUES
(1467, 'นาย', 'พงษ์เพชร อ่อนม่วง', NULL, NULL, '1883', 'Programmer', 'Human Resources', 'JAS', NULL, 'HRBP', 'JIT29', NULL, 'pongpech.on@jasmine.com', 'IT Programmer ป.ตรี', 'jas', NULL, 'เชี่ยวชาญภาษาจีน', '1883', '1883', '2021-07-05 08:28:43', NULL, NULL, 'IT, Network , Marketing', 'basic IT', 5, '2564', NULL, NULL, NULL, NULL, NULL),
(1469, 'น.ส.', 'ศศิวิมล อนุเศรษฐพงศ์', NULL, NULL, '1808', 'Supervisor', 'Human Resources', 'JAS', NULL, 'HRD', 'JIT29', '3026', 'sasiwimon.a@jasmine.com', 'ป.โท เอกภาษาอังกฤษ', 'jas , ครูสอนภาษาอังกฤษ', 'asdasdas', NULL, '1883', '1883', '2021-07-06 11:41:46', NULL, '15000 บาท/วัน', 'ภาษาอังกฤษ', 'ENG_101', 12, '2564', NULL, NULL, NULL, NULL, NULL),
(1487, 'นาย', 'อัครพล โคตรจันทร์', NULL, NULL, '1861', 'Senior Officer', NULL, 'JAS', NULL, 'HRD', 'JIT29', '3086', 'axkarapol.s@jasmine.com', 'ป.โท ภาษาจีน', 'ครูสอนภาษาจีน', NULL, NULL, '1883', NULL, '2021-07-05 04:34:30', NULL, NULL, 'ภาษาจีน', 'CHI-102,CHI-103,CHI-104', 2, '2564', NULL, NULL, NULL, NULL, NULL),
(1489, 'นาย', 'ศักดิ์สิทธิ์ แท่งทอง', 'saksit', 'tangthong', NULL, 'Network Engineer', NULL, 'ITCS', NULL, NULL, 'ITCS', '0987654322', 'saksit@gmail.com', 'ป.โท Network Engineer KMUTNB', 'IME, TPP,FPP', NULL, 'ทำงานเป็นทีมได้ไม่ดี', '1883', '1883', '2021-07-05 06:55:31', 'assets/img/trainer/1489_saksit_tangthong_2021-07-05_08-53-21_.jpg', '15000 บาท/วัน', 'Network Security', 'NS-101', 5, '2563', NULL, '1489_saksit_tangthong_2021-07-05_08-53-21_.jpg', NULL, NULL, NULL),
(1492, 'นาย', 'สันติ มากมาย', NULL, NULL, '1721JAS', 'Assistant Manager', NULL, 'JAS', NULL, 'HRD', 'JIT29', '3024 ', 'santi.m@jasmine.com', 'ป.ตรี', 'JAS', NULL, NULL, '1883', NULL, '2021-07-05 08:35:09', NULL, NULL, 'video', 'video creator', 12, '2564', NULL, NULL, NULL, NULL, NULL),
(1493, 'นาย', 'ณพนรรจ์ ตั้งศิลาถาวร', NULL, NULL, '3492JAS', 'Senior System Analyst', NULL, 'JAS', NULL, 'HRD', 'JIT29', '3140', 'noppanan.t@jasmine.com', NULL, NULL, NULL, NULL, '1883', NULL, '2021-07-05 08:38:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1496, 'นาย', 'ศรราม เทพพิทักษ์', 'sornram', 'teppitak', NULL, 'Supervisor', NULL, 'CCS', NULL, NULL, 'ITCS', '098765432121', 'sornram@gmail.com', 'aaaaaaaaaaa', NULL, NULL, 'sdasdasdas', '1883', '1883', '2021-07-13 08:11:10', 'assets/img/trainer/1496_sornram_teppitak_2021-07-11_21-42-41_.jpg', '20000 บาท/วัน', 'ภาษาจีน', 'CHI-102,CHI-103,CHI-104', 5, '2564', NULL, '1496_sornram_teppitak_2021-07-11_21-42-41_.jpg', 'คุณ นัท', '0987654321', 'nat@email.com');

-- --------------------------------------------------------

--
-- Table structure for table `trainingsite`
--

CREATE TABLE `trainingsite` (
  `siteid` bigint(20) UNSIGNED NOT NULL,
  `sitename` varchar(100) NOT NULL,
  `siteroom` varchar(50) DEFAULT NULL,
  `sitefloor` varchar(4) DEFAULT NULL,
  `siteprovince` varchar(60) NOT NULL,
  `sitero` smallint(6) DEFAULT NULL,
  `contactname` varchar(60) DEFAULT NULL,
  `contactposition` varchar(60) DEFAULT NULL,
  `contacttelephone` varchar(30) DEFAULT NULL,
  `contactemail` varchar(50) DEFAULT NULL,
  `siteurl` varchar(60) DEFAULT NULL,
  `siteremark` varchar(100) DEFAULT NULL,
  `sitelat` float DEFAULT NULL,
  `sitelong` float DEFAULT NULL,
  `createby` varchar(10) DEFAULT NULL,
  `updateby` varchar(10) DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `courseallocate`
--
ALTER TABLE `courseallocate`
  ADD PRIMARY KEY (`allocateID`);

--
-- Indexes for table `courselog`
--
ALTER TABLE `courselog`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `coursemaster`
--
ALTER TABLE `coursemaster`
  ADD PRIMARY KEY (`coursemasterID`);

--
-- Indexes for table `courseschedule`
--
ALTER TABLE `courseschedule`
  ADD PRIMARY KEY (`scheduleID`);

--
-- Indexes for table `coursestudent`
--
ALTER TABLE `coursestudent`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `paramcode`
--
ALTER TABLE `paramcode`
  ADD PRIMARY KEY (`codeID`);

--
-- Indexes for table `paramprovince`
--
ALTER TABLE `paramprovince`
  ADD PRIMARY KEY (`provinceID`);

--
-- Indexes for table `paramtag`
--
ALTER TABLE `paramtag`
  ADD PRIMARY KEY (`tagID`);

--
-- Indexes for table `studentlog`
--
ALTER TABLE `studentlog`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `tisusers`
--
ALTER TABLE `tisusers`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
  ADD PRIMARY KEY (`trainerID`);

--
-- Indexes for table `trainingsite`
--
ALTER TABLE `trainingsite`
  ADD PRIMARY KEY (`siteid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1224;

--
-- AUTO_INCREMENT for table `courseallocate`
--
ALTER TABLE `courseallocate`
  MODIFY `allocateID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courselog`
--
ALTER TABLE `courselog`
  MODIFY `logID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coursemaster`
--
ALTER TABLE `coursemaster`
  MODIFY `coursemasterID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1222;

--
-- AUTO_INCREMENT for table `courseschedule`
--
ALTER TABLE `courseschedule`
  MODIFY `scheduleID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coursestudent`
--
ALTER TABLE `coursestudent`
  MODIFY `studentID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paramcode`
--
ALTER TABLE `paramcode`
  MODIFY `codeID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paramprovince`
--
ALTER TABLE `paramprovince`
  MODIFY `provinceID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `paramtag`
--
ALTER TABLE `paramtag`
  MODIFY `tagID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `studentlog`
--
ALTER TABLE `studentlog`
  MODIFY `logID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tisusers`
--
ALTER TABLE `tisusers`
  MODIFY `userID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60003;

--
-- AUTO_INCREMENT for table `trainer`
--
ALTER TABLE `trainer`
  MODIFY `trainerID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1497;

--
-- AUTO_INCREMENT for table `trainingsite`
--
ALTER TABLE `trainingsite`
  MODIFY `siteid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
