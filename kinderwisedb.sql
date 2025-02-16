-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 02:05 PM
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
-- Database: `kinderwisedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `administratorID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contactNumber` varchar(11) DEFAULT NULL,
  `password` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administratorID`, `name`, `email`, `contactNumber`, `password`) VALUES
(1, 'Kuan Chee Ling', 'kuancheeling@kinderwise.com', '01165571024', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcementID` int(11) NOT NULL,
  `announcementTitle` varchar(50) NOT NULL,
  `details` varchar(300) DEFAULT NULL,
  `postDate` date DEFAULT NULL,
  `teacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcementID`, `announcementTitle`, `details`, `postDate`, `teacherID`) VALUES
(NULL, 'Parent-Teacher Meeting Reminder', "Dear Parents and Guardians,\r\nWe would like to remind you about the upcoming Parent-Teacher Meeting to discuss your child\'s progress, 
  share feedback, and strengthen our partnership.\r\n\r\nDetails of the Meeting:\r\n\r\nDate: 14-07-2025\r\nTime: 10am-12pm\r\n'", '2025-02-13', 1),

(NULL, 'Fun Friday: Dress Up Day Ahead!', "Get ready for a fun-filled Friday! \r\n\r\nThis week, we’re having a Dress Up Day, 
  so come to school in your favorite costume or outfit. \r\n\r\nLet’s make it a day full of creativity and excitement. \r\nWe can\'t wait to see everyone\'s amazing looks!", '2025-02-13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessmentID` int(11) NOT NULL,
  `assessmentType` varchar(30) DEFAULT NULL,
  `teacherID` int(11) DEFAULT NULL,
  `subjectName` varchar(50) DEFAULT NULL,
  `semesterCode` varchar(20) DEFAULT NULL,
  `yearCode` varchar(20) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `postedOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'no submission'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`assessmentID`, `assessmentType`, `teacherID`, `subjectName`, `semesterCode`, `yearCode`, `description`, `postedOn`, `deadline`, `status`) VALUES
(1, 'Finals', 1, 'English Y1', 'Sem2Y1', 'Year1', 'English Year 1 Sem 2 Finals', '2025-10-08 00:00:00', '2025-08-01', 'no submission'),
(2, 'Finals', 1, 'Mandarin Y1', 'Sem2Y1', 'Year1', 'Mandarin Year 1 Sem 2 Finals', '2025-10-01 00:00:00', '2025-07-23', 'no submission'),
(3, 'Midterm', 1, 'Science Y1', 'Sem1Y1', 'Year1', 'Science Year 1 Sem 1 Midterm', '2025-03-28 00:00:00', '2025-02-11', 'no submission'),
(4, 'Midterm', 1, 'English Y1', 'Sem1Y1', 'Year1', 'English Year 1 Sem 1 Midterm', '2025-02-14 00:00:00', '2025-01-15', 'no submission'),
(5, 'Midterm', 1, 'Mathematics Y1', 'Sem1Y1', 'Year1', 'Mathematics Year 1 Sem 1 Midterm', '2025-01-24 00:00:00', '2025-01-03', 'no submission'),
(6, 'Midterm', 1, 'Mandarin Y1', 'Sem1Y1', 'Year1', 'Mandarin Year 1 Sem 1 Midterm', '2025-02-01 00:00:00', '2025-03-26', 'no submission'),
(7, 'Midterm', 1, 'Bahasa Malaysia Y1', 'Sem1Y1', 'Year1', 'Bahasa Malaysia Year 1 Sem 1 Midterm', '2025-02-01 00:00:00', '2025-03-27', 'no submission');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `classCode` varchar(20) NOT NULL,
  `teacherID` int(11) DEFAULT NULL,
  `yearCode` varchar(20) DEFAULT NULL,
  `classCapacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`classCode`, `teacherID`, `yearCode`, `classCapacity`) VALUES
('BlueY1', 2, 'Year1', 5),
('BlueY2', 3, 'Year2', 5),
('BlueY3', 4, 'Year3', 5),
('GreenY1', 1, 'Year1', 5),
('GreenY2', 5, 'Year2', 5),
('GreenY3', 6, 'Year3', 5),
('PurpleY1', 7, 'Year1', 5),
('PurpleY2', 8, 'Year2', 5),
('PurpleY3', NULL, 'Year3', NULL),
('RedY1', 10, 'Year1', 5),
('RedY2', 11, 'Year2', 5),
('RedY3', 12, 'Year3', 5),
('YellowY1', 13, 'Year1', 5),
('YellowY2', 14, 'Year2', 5),
('YellowY3', 9, 'Year3', 5);

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `parentID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contactNumber` varchar(11) DEFAULT NULL,
  `password` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`parentID`, `name`, `email`, `contactNumber`, `password`) VALUES
(1, 'Yap Yan Ting', 'yapyanting@kinderwise.com', '0162738739', 'yanting'),
(2, 'Tan Ah Kau', 'ah.kau@kinderwise.com', '0112347856', 'tankau88'),
(3, 'Noraini Hassan', 'noraini.h@kinderwise.com', '0134567891', 'noraini1'),
(4, 'Jenny Teo', 'jenny.t@kinderwise.com', '0198765432', 'jenny888'),
(5, 'Zulkifli Ibrahim', 'zul.i@kinderwise.com', '0145678912', 'zul12345'),
(6, 'Meena Kumari', 'meena.k@kinderwise.com', '0111234567', 'meena123'),
(7, 'Wong Siew Mei', 'siew.mei@kinderwise.com', '0123789456', 'wong1234'),
(8, 'Kamal Bahrin', 'kamal.b@kinderwise.com', '0139876543', 'kamal123'),
(9, 'Krishna Dev', 'krishna.d@kinderwise.com', '0198234567', 'krishna1'),
(10, 'Rose Chan', 'rose.c@kinderwise.com', '0114567890', 'rose1234'),
(11, 'Ahmad Rizal', 'ahmad.r@kinderwise.com', '0123567894', 'ahmad123'),
(12, 'Lim Chee Seng', 'chee.s@kinderwise.com', '0137894561', 'lim12345'),
(13, 'Kavitha Raj', 'kavitha.r@kinderwise.com', '0198901234', 'kavi1234'),
(14, 'Azlina Tan', 'azlina.t@kinderwise.com', '0112345678', 'azlina12'),
(15, 'Gopal Krishnan', 'gopal.k@kinderwise.com', '0123456798', 'gopal123'),
(16, 'Faridah Zainal', 'faridah.z@kinderwise.com', '0134567892', 'farah123'),
(17, 'Bernard Chew', 'bernard.c@kinderwise.com', '0198765433', 'bernard1'),
(18, 'Siti Aishah', 'siti.a@kinderwise.com', '0145678913', 'siti1234'),
(19, 'David Lau', 'david.l@kinderwise.com', '0111234568', 'david888'),
(20, 'Norhaslinda', 'linda.n@kinderwise.com', '0123789457', 'linda123'),
(21, 'Ramesh Singh', 'ramesh.s@kinderwise.com', '0139876544', 'ramesh12'),
(22, 'Michelle Wong', 'michelle.w@kinderwise.com', '0198234568', 'mich1234'),
(23, 'Hassan Ali', 'hassan.a@kinderwise.com', '0114567891', 'hassan12'),
(24, 'Catherine Loh', 'cath.l@kinderwise.com', '0123567895', 'cath1234'),
(25, 'Muthu Kumar', 'muthu.k@kinderwise.com', '0137894562', 'muthu123'),
(26, 'Peter Koh', 'peter.k@kinderwise.com', '0198901235', 'peter888'),
(27, 'Zarina Ahmad', 'zarina.a@kinderwise.com', '0112345679', 'zarina12'),
(28, 'Edwin Tong', 'edwin.t@kinderwise.com', '0123456799', 'edwin123'),
(29, 'Sundari Devi', 'sundari.d@kinderwise.com', '0134567893', 'sunda123'),
(30, 'Idris Salleh', 'idris.s@kinderwise.com', '0198765434', 'idris123'),
(31, 'Alice Yap', 'alice.y@kinderwise.com', '0145678914', 'alice888'),
(32, 'Raju Moorthy', 'raju.m@kinderwise.com', '0111234569', 'raju1234'),
(33, 'Hafizah Lim', 'hafizah.l@kinderwise.com', '0123789458', 'hafiz123'),
(34, 'Vijay Shankar', 'vijay.s@kinderwise.com', '0139876545', 'vijay123'),
(35, 'Lily Chong', 'lily.c@kinderwise.com', '0198234569', 'lily8888'),
(36, 'Amir Hamzah', 'amir.h@kinderwise.com', '0114567892', 'amir1234'),
(37, 'Teresa Ng', 'teresa.n@kinderwise.com', '0123567896', 'teresa12'),
(38, 'Salim Iskandar', 'salim.i@kinderwise.com', '0137894563', 'salim123'),
(39, 'Florence Tan', 'florence.t@kinderwise.com', '0198901236', 'flor1234'),
(40, 'Mariam Hassan', 'mariam.h@kinderwise.com', '0112345680', 'mariam12'),
(41, 'Jimmy Chia', 'jimmy.c@kinderwise.com', '0123456800', 'jimmy888'),
(42, 'Fatimah Zahra', 'fatimah.z@kinderwise.com', '0134567894', 'fatima12'),
(43, 'Saravanan', 'sara.v@kinderwise.com', '0198765435', 'sara1234'),
(44, 'Melissa Yeoh', 'melissa.y@kinderwise.com', '0145678915', 'mel12345'),
(45, 'Razak Ibrahim', 'razak.i@kinderwise.com', '0111234570', 'razak123'),
(46, 'Teh Soo Lin', 'soo.lin@kinderwise.com', '0123789459', 'tehsoo12'),
(47, 'Karim Badawi', 'karim.b@kinderwise.com', '0139876546', 'karim123'),
(48, 'Uma Devi', 'uma.d@kinderwise.com', '0198234570', 'uma12345'),
(49, 'Lee Wei Ming', 'wei.ming@kinderwise.com', '0114567893', 'lee12345'),
(50, 'Sharifah Aisha', 'sharifah.a@kinderwise.com', '0123567897', 'aisha123'),
(51, 'Cheong Wai Kit', 'wai.kit@kinderwise.com', '0137894564', 'cheong12'),
(52, 'Noor Azlan', 'noor.a@kinderwise.com', '0198901237', 'noor1234'),
(53, 'Lakshmi Sri', 'lakshmi.s@kinderwise.com', '0112345681', 'laksh123'),
(54, 'Mohd Nazri', 'nazri.m@kinderwise.com', '0123456801', 'nazri123'),
(55, 'Anand Raja', 'anand.r@kinderwise.com', '0134567895', 'anand123'),
(56, 'Chan Kok Wei', 'kok.wei@kinderwise.com', '0198765436', 'chan1234'),
(57, 'Aminah Yusuf', 'aminah.y@kinderwise.com', '0145678916', 'aminah12'),
(58, 'Subramaniam', 'subra.m@kinderwise.com', '0111234571', 'subra123'),
(59, 'Low Siew Chin', 'siew.c@kinderwise.com', '0123789460', 'low12345'),
(60, 'Azman Yusof', 'azman.y@kinderwise.com', '0139876547', 'azman123'),
(61, 'Ng Mei Ling', 'mei.ling@kinderwise.com', '0198234571', 'ng123456'),
(62, 'Ramlah Aziz', 'ramlah.a@kinderwise.com', '0114567894', 'ramlah12'),
(63, 'Shanti Mala', 'shanti.m@kinderwise.com', '0123567898', 'shanti12'),
(64, 'Chow Pak Leong', 'pak.l@kinderwise.com', '0137894565', 'chow1234'),
(65, 'Mastura Hamid', 'mastura.h@kinderwise.com', '0198901238', 'mas12345'),
(66, 'Goh Mei Yan', 'mei.yan@kinderwise.com', '0112345682', 'goh12345'),
(67, 'Khairul Anwar', 'khairul.a@kinderwise.com', '0123456802', 'khair123'),
(68, 'Siva Raman', 'siva.r@kinderwise.com', '0134567896', 'siva1234'),
(69, 'Yong Ah Moi', 'ah.moi@kinderwise.com', '0198765437', 'yong1234'),
(70, 'Ismail Abdullah', 'ismail.a@kinderwise.com', '0145678917', 'ismail12');

-- --------------------------------------------------------

--
-- Table structure for table `principal`
--

CREATE TABLE `principal` (
  `principalID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contactNumber` varchar(11) DEFAULT NULL,
  `password` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `principal`
--

INSERT INTO `principal` (`principalID`, `name`, `email`, `contactNumber`, `password`) VALUES
(1, 'Yap Sze Thin', 'yapszethin@kinderwise.com', '0108140417', 'szethin');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `resultID` int(11) NOT NULL,
  `finalScore` int(11) DEFAULT NULL,
  `studentID` int(11) NOT NULL,
  `assessmentID` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'unverified' CHECK (`status` in ('verified','unverified'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`resultID`, `finalScore`, `studentID`, `assessmentID`, `status`) VALUES
(1, 80, 1, 3, 'unverified'),
(2, 100, 1, 4, 'unverified'),
(3, 99, 1, 5, 'unverified'),
(4, 80, 1, 6, 'unverified'),
(5, 86, 1, 7, 'unverified'),
(6, 40, 2, 3, 'unverified'),
(7, 80, 2, 4, 'unverified'),
(8, 40, 2, 5, 'unverified'),
(9, 80, 2, 6, 'unverified'),
(10, 86, 2, 7, 'unverified'),
(11, 90, 3, 3, 'unverified'),
(12, 90, 3, 4, 'unverified'),
(13, 99, 3, 5, 'unverified'),
(14, 80, 3, 6, 'unverified'),
(15, 86, 3, 7, 'unverified'),
(16, 100, 4, 3, 'unverified'),
(17, 100, 4, 4, 'unverified'),
(18, 99, 4, 5, 'unverified'),
(19, 80, 4, 6, 'unverified'),
(20, 86, 4, 7, 'unverified'),
(21, 80, 5, 3, 'unverified'),
(22, 80, 5, 4, 'unverified'),
(23, 99, 5, 5, 'unverified'),
(24, 80, 5, 6, 'unverified'),
(25, 86, 5, 7, 'unverified'),
(26, 91, 17, 3, 'unverified'), 
(27, '80', '17', '4', 'unverified'), 
(28, '87', '17', '5', 'unverified'), 
(29, '99', '17', '6', 'unverified'), 
(30, '91', '17', '7', 'unverified'), 
(31, '88', '19', '3', 'unverified'), 
(32, '86', '19', '4', 'unverified'), 
(33, '73', '19', '5', 'unverified'), 
(34, '91', '19', '6', 'unverified'),
(35, '80', '19', '7', 'unverified'), 
(36, NULL, '20', '3', 'unverified'), 
(37, '78', '20', '4', 'unverified'), 
(38, '77', '20', '5', 'unverified'), 
(39, '89', '20', '6', 'unverified'),
(40, '95', '20', '7', 'unverified');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semesterCode` varchar(20) NOT NULL,
  `yearCode` varchar(20) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semesterCode`, `yearCode`, `startDate`, `endDate`) VALUES
('Sem1Y1', 'Year1', '2025-01-01', '2025-05-31'),
('Sem1Y2', 'Year2', '2025-01-01', '2025-05-31'),
('Sem1Y3', 'Year3', '2025-01-01', '2025-05-31'),
('Sem2Y1', 'Year1', '2025-06-01', '2025-10-31'),
('Sem2Y2', 'Year2', '2025-06-01', '2025-10-31'),
('Sem2Y3', 'Year3', '2025-06-01', '2025-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `parentID` int(11) DEFAULT NULL,
  `classCode` varchar(20) DEFAULT NULL,
  `yearCode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `name`, `age`, `birthday`, `gender`, `parentID`, `classCode`, `yearCode`) VALUES
(1, 'Yap Zhang Jin', 4, '2020-03-15', 'Male', 1, 'BlueY1', 'Year1'),
(2, 'Tan Ming Jun', 4, '2020-04-22', 'Male', 2, 'BlueY1', 'Year1'),
(3, 'Noraini Amira', 4, '2020-05-18', 'Female', 3, 'BlueY1', 'Year1'),
(4, 'Teo Wei Ling', 4, '2020-06-25', 'Female', 4, 'BlueY1', 'Year1'),
(5, 'Ibrahim Haziq', 4, '2020-07-30', 'Male', 5, 'BlueY1', 'Year1'),
(6, 'Kumari Preethi', 4, '2020-02-12', 'Female', 6, 'RedY1', 'Year1'),
(7, 'Wong Jun Kai', 4, '2020-03-20', 'Male', 7, 'RedY1', 'Year1'),
(8, 'Bahrin Aqil', 4, '2020-04-28', 'Male', 8, 'RedY1', 'Year1'),
(9, 'Dev Aryan', 4, '2020-06-05', 'Male', 9, 'RedY1', 'Year1'),
(10, 'Chan Li Mei', 4, '2020-08-15', 'Female', 10, 'RedY1', 'Year1'),
(11, 'Rizal Danish', 4, '2020-01-25', 'Male', 11, 'YellowY1', 'Year1'),
(12, 'Lim Xiao Mei', 4, '2020-03-28', 'Female', 12, 'YellowY1', 'Year1'),
(13, 'Raj Karthik', 4, '2020-05-15', 'Male', 13, 'YellowY1', 'Year1'),
(14, 'Tan Hui Min', 4, '2020-07-22', 'Female', 14, 'YellowY1', 'Year1'),
(15, 'Krishnan Vimal', 4, '2020-09-30', 'Male', 15, 'YellowY1', 'Year1'),
(16, 'Zainal Amirah', 4, '2020-02-18', 'Female', 16, 'GreenY1', 'Year1'),
(17, 'Chew Jia Yu', 4, '2020-04-25', 'Female', 17, 'GreenY1', 'Year1'),
(18, 'Aishah Nurin', 4, '2020-06-12', 'Female', 18, 'GreenY1', 'Year1'),
(19, 'Lau Jin Wei', 4, '2020-08-20', 'Male', 19, 'GreenY1', 'Year1'),
(20, 'Linda Sofea', 4, '2020-10-15', 'Female', 20, 'GreenY1', 'Year1'),
(21, 'Singh Ishaan', 4, '2020-02-28', 'Male', 21, 'PurpleY1', 'Year1'),
(22, 'Wong Mei Xin', 4, '2020-04-15', 'Female', 22, 'PurpleY1', 'Year1'),
(23, 'Ali Imran', 4, '2020-06-22', 'Male', 23, 'PurpleY1', 'Year1'),
(24, 'Loh Jun Yi', 4, '2020-08-30', 'Male', 24, 'PurpleY1', 'Year1'),
(25, 'Kumar Divya', 4, '2020-10-25', 'Female', 25, 'PurpleY1', 'Year1'),
(26, 'Koh Wei Jie', 5, '2019-02-15', 'Male', 26, 'BlueY2', 'Year2'),
(27, 'Ahmad Rayyan', 5, '2019-03-22', 'Male', 27, 'BlueY2', 'Year2'),
(28, 'Tong Xin Yi', 5, '2019-05-18', 'Female', 28, 'BlueY2', 'Year2'),
(29, 'Devi Anita', 5, '2019-07-25', 'Female', 29, 'BlueY2', 'Year2'),
(30, 'Salleh Irfan', 5, '2019-09-30', 'Male', 30, 'BlueY2', 'Year2'),
(31, 'Yap Li Hua', 5, '2019-01-12', 'Female', 31, 'RedY2', 'Year2'),
(32, 'Moorthy Kavi', 5, '2019-03-20', 'Male', 32, 'RedY2', 'Year2'),
(33, 'Lim Zhi Yang', 5, '2019-05-28', 'Male', 33, 'RedY2', 'Year2'),
(34, 'Shankar Deepa', 5, '2019-07-15', 'Female', 34, 'RedY2', 'Year2'),
(35, 'Chong Wei Chen', 5, '2019-09-22', 'Male', 35, 'RedY2', 'Year2'),
(36, 'Hamzah Adil', 5, '2019-02-18', 'Male', 36, 'YellowY2', 'Year2'),
(37, 'Ng Hui Wen', 5, '2019-04-25', 'Female', 37, 'YellowY2', 'Year2'),
(38, 'Iskandar Zain', 5, '2019-06-12', 'Male', 38, 'YellowY2', 'Year2'),
(39, 'Tan Yu Xin', 5, '2019-08-20', 'Female', 39, 'YellowY2', 'Year2'),
(40, 'Hassan Iman', 5, '2019-10-15', 'Male', 40, 'YellowY2', 'Year2'),
(41, 'Chia Ming Han', 5, '2019-01-28', 'Male', 41, 'GreenY2', 'Year2'),
(42, 'Zahra Aliya', 5, '2019-03-15', 'Female', 42, 'GreenY2', 'Year2'),
(43, 'Saravanan Aarav', 5, '2019-05-22', 'Male', 43, 'GreenY2', 'Year2'),
(44, 'Yeoh Jia Qi', 5, '2019-07-30', 'Female', 44, 'GreenY2', 'Year2'),
(45, 'Ibrahim Rayyan', 5, '2019-09-25', 'Male', 45, 'GreenY2', 'Year2'),
(46, 'Soo Cheng', 5, '2019-02-12', 'Female', 46, 'PurpleY2', 'Year2'),
(47, 'Badawi Danish', 5, '2019-04-20', 'Male', 47, 'PurpleY2', 'Year2'),
(48, 'Devi Shreya', 5, '2019-06-28', 'Female', 48, 'PurpleY2', 'Year2'),
(49, 'Lee Jia Jun', 5, '2019-08-15', 'Male', 49, 'PurpleY2', 'Year2'),
(50, 'Aisha Sofiya', 5, '2019-10-22', 'Female', 50, 'PurpleY2', 'Year2'),
(51, 'Wai Kit Jun', 6, '2018-01-15', 'Male', 51, 'BlueY3', 'Year3'),
(52, 'Azlan Amir', 6, '2018-03-22', 'Male', 52, 'BlueY3', 'Year3'),
(53, 'Sri Lakshmi', 6, '2018-05-18', 'Female', 53, 'BlueY3', 'Year3'),
(54, 'Nazri Hakim', 6, '2018-07-25', 'Male', 54, 'BlueY3', 'Year3'),
(55, 'Raja Arvin', 6, '2018-09-30', 'Male', 55, 'BlueY3', 'Year3'),
(56, 'Wei Xiang', 6, '2018-02-12', 'Male', 56, 'RedY3', 'Year3'),
(57, 'Yusuf Amira', 6, '2018-04-20', 'Female', 57, 'RedY3', 'Year3'),
(58, 'Subramaniam Vikram', 6, '2018-06-28', 'Male', 58, 'RedY3', 'Year3'),
(59, 'Chin Hui Yi', 6, '2018-08-15', 'Female', 59, 'RedY3', 'Year3'),
(60, 'Yusof Adam', 6, '2018-10-22', 'Male', 60, 'RedY3', 'Year3'),
(61, 'Mei Ling Jun Yi', 6, '2018-01-28', 'Male', 61, 'YellowY3', 'Year3'),
(62, 'Aziz Nur Aina', 6, '2018-03-15', 'Female', 62, 'YellowY3', 'Year3'),
(63, 'Mala Rahul', 6, '2018-05-22', 'Male', 63, 'YellowY3', 'Year3'),
(64, 'Leong Yu Ting', 6, '2018-07-30', 'Female', 64, 'YellowY3', 'Year3'),
(65, 'Hamid Irfan', 6, '2018-09-25', 'Male', 65, 'YellowY3', 'Year3'),
(66, 'Mei Yan Xin', 6, '2018-02-12', 'Female', 66, 'GreenY3', 'Year3'),
(67, 'Anwar Danish', 6, '2018-04-20', 'Male', 67, 'GreenY3', 'Year3'),
(68, 'Raman Darshan', 6, '2018-06-28', 'Male', 68, 'GreenY3', 'Year3'),
(69, 'Ah Moi Lin', 6, '2018-08-15', 'Female', 69, 'GreenY3', 'Year3'),
(70, 'Abdullah Aisha', 6, '2018-10-22', 'Female', 70, 'GreenY3', 'Year3');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectName` varchar(50) NOT NULL,
  `yearCode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectName`, `yearCode`) VALUES
('Bahasa Malaysia Y1', 'Year1'),
('English Y1', 'Year1'),
('Mandarin Y1', 'Year1'),
('Mathematics Y1', 'Year1'),
('Science Y1', 'Year1'),
('Bahasa Malaysia Y2', 'Year2'),
('English Y2', 'Year2'),
('Mandarin Y2', 'Year2'),
('Mathematics Y2', 'Year2'),
('Science Y2', 'Year2'),
('Bahasa Malaysia Y3', 'Year3'),
('English Y3', 'Year3'),
('Mandarin Y3', 'Year3'),
('Mathematics Y3', 'Year3'),
('Science Y3', 'Year3');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacherID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contactNumber` varchar(11) DEFAULT NULL,
  `password` varchar(8) DEFAULT NULL,
  `classAssigned` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacherID`, `name`, `email`, `contactNumber`, `password`, `classAssigned`) VALUES
(1, 'Ngan Li Syuen', 'nganlisyuen@kinderwise.com', '0122680160', 'lisyuen', 'GreenY1'),
(2, 'Revathi', 'revathi@kinderwise.com', '0155638796', 'revathi', 'BlueY1'),
(3, 'Alice Tan', 'alice.tan@kinderwise.com', '01167892345', 'alicetan', 'BlueY2'),
(4, 'Aiman Syukri', 'aiman.syukri@kinderwise.com', '0155786124', 'aiman123', 'BlueY3'),
(5, 'Priya Nandini', 'priya.nandini@kinderwise.com', '0123695499', 'priya789', 'GreenY2'),
(6, 'Chong Wei Han', 'chong.weihan@kinderwise.com', '0145623145', 'weihan88', 'GreenY3'),
(7, 'Siti Farhana', 'siti.farhana@kinderwise.co', '0117845623', 'siti0008', 'PurpleY1'),
(8, 'Ravi Kumar', 'ravi.kumar@kinderwise.com', '0124789563', 'ravi4321', 'PurpleY2'),
(9, 'Tan Jia Ying', 'tan.jiaying@kinderwise.com', '0136987452', 'jiaying7', 'YellowY3'),
(10, 'Amirul Hakim', 'amirul.hakim@kinderwise.com', '0112365987', 'amirul22', 'RedY1'),
(11, 'Divya Menon', 'divya.menon@kinderwise.com', '0123256987', 'divya999', 'RedY2'),
(12, 'Goh Yi Ting', 'goh.yiting@kinderwise.com', '0145698741', 'yiting45', 'RedY3'),
(13, 'Roslan Hafeez', 'roslan.hafeez@kinderwise.com', '0102587963', 'roslan77', 'YellowY1'),
(14, 'Selvi Arumugam', 'selvi.arumugam@kinderwise.com', '01147852136', 'selvi888', 'YellowY2'),
(15, 'Song Man Tou', 'songmantou@kinderwise.com', '0112345678', 'song', NULL);


-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `yearCode` varchar(20) NOT NULL,
  `description` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`yearCode`, `description`) VALUES
('Year1', 'Foundational learning with basic skills.'),
('Year2', 'Building on basics and simple explorations.'),
('Year3', 'Advanced concepts and structured learning.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administratorID`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcementID`),
  ADD KEY `fk_announcement_teacher` (`teacherID`);

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessmentID`),
  ADD KEY `fk_assessment_teacher` (`teacherID`),
  ADD KEY `fk_assessment_subject` (`subjectName`),
  ADD KEY `fk_assessment_semester` (`semesterCode`),
  ADD KEY `fk_assessment_year` (`yearCode`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`classCode`),
  ADD KEY `fk_class_teacher` (`teacherID`),
  ADD KEY `fk_class_year` (`yearCode`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`parentID`);

--
-- Indexes for table `principal`
--
ALTER TABLE `principal`
  ADD PRIMARY KEY (`principalID`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`resultID`),
  ADD KEY `fk_result_assessment` (`assessmentID`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semesterCode`),
  ADD KEY `fk_semester_year` (`yearCode`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `fk_student_parent` (`parentID`),
  ADD KEY `fk_student_class` (`classCode`),
  ADD KEY `fk_student_year` (`yearCode`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectName`),
  ADD KEY `fk_subject_year` (`yearCode`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacherID`),
  ADD KEY `fk_teacher_class` (`classAssigned`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`yearCode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `administratorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
  MODIFY `assessmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `parentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `principal`
--
ALTER TABLE `principal`
  MODIFY `principalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- AUTO_INCREMENT for table `result`
--
ALTER TABLE `result`
  MODIFY `resultID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `fk_announcement_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teacher` (`teacherID`);

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `fk_assessment_semester` FOREIGN KEY (`semesterCode`) REFERENCES `semester` (`semesterCode`),
  ADD CONSTRAINT `fk_assessment_subject` FOREIGN KEY (`subjectName`) REFERENCES `subject` (`subjectName`),
  ADD CONSTRAINT `fk_assessment_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teacher` (`teacherID`),
  ADD CONSTRAINT `fk_assessment_year` FOREIGN KEY (`yearCode`) REFERENCES `year` (`yearCode`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `fk_class_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teacher` (`teacherID`),
  ADD CONSTRAINT `fk_class_year` FOREIGN KEY (`yearCode`) REFERENCES `year` (`yearCode`);

--
-- Constraints for table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `fk_result_assessment` FOREIGN KEY (`assessmentID`) REFERENCES `assessment` (`assessmentID`),
  ADD CONSTRAINT `fk_result_student` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`);

--
-- Constraints for table `semester`
--
ALTER TABLE `semester`
  ADD CONSTRAINT `fk_semester_year` FOREIGN KEY (`yearCode`) REFERENCES `year` (`yearCode`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_class` FOREIGN KEY (`classCode`) REFERENCES `class` (`classCode`),
  ADD CONSTRAINT `fk_student_parent` FOREIGN KEY (`parentID`) REFERENCES `parent` (`parentID`),
  ADD CONSTRAINT `fk_student_year` FOREIGN KEY (`yearCode`) REFERENCES `year` (`yearCode`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `fk_subject_year` FOREIGN KEY (`yearCode`) REFERENCES `year` (`yearCode`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacher_class` FOREIGN KEY (`classAssigned`) REFERENCES `class` (`classCode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
