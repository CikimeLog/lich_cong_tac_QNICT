-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 20, 2019 at 06:56 PM
-- Server version: 5.6.33
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lichcongtacstt_lct`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `username` varchar(50) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `hash` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `auth` tinyint(4) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `id_Department` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`username`, `hash`, `auth`, `name`, `id_Department`) VALUES
('admin', '$2y$12$hmEQJoMGaAkI57RyYqZ8iuCKGWekNl0slrWk/WSY8z3965.zr53Ka', 1, 'admin', 1),
('admin.thuy', '$2y$12$dmJkvPok.b7ASPOnepNH9eiw1ec2zfWqAvpg84MbPXmrEhN/N6keO', 1, 'Lê Thị Thúy', 1),
('admin.trang', '$2y$12$aZ4mt4aCS3kipSyhyTgfmujFlyRePG31rwkL3JiA8SHYBnKIdiB6q', 1, 'Phạm Thị Trang', 1),
('admin1', '$2y$12$aQgUyzpt1bomTCblpMMTHuVbupsRwOOEs8ovH0TVkmWyBbc2ACuji', 1, 'cikimelog', 1),
('admin2', '$2y$12$ndIuk8zxrdbyZxvWH.r5MubyflIRED5wOyp.rEL5AoALxwuslTeM6', 1, 'cikimelog', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartment`
--

CREATE TABLE `tbldepartment` (
  `Department_ID` tinyint(3) UNSIGNED NOT NULL,
  `Department_Name` text COLLATE utf8mb4_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tbldepartment`
--

INSERT INTO `tbldepartment` (`Department_ID`, `Department_Name`) VALUES
(1, 'CƠ QUAN'),
(3, 'Phòng hành chính tổng hợp'),
(5, 'Phòng nghiên cứu, ứng dụng và phát triển');

-- --------------------------------------------------------

--
-- Table structure for table `tblevent`
--

CREATE TABLE `tblevent` (
  `Event_ID` int(10) UNSIGNED NOT NULL,
  `Event_TitleName` text COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `Event_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tblevent`
--

INSERT INTO `tblevent` (`Event_ID`, `Event_TitleName`, `Event_Date`) VALUES
(13, 'Chúc mừng ngày thành lập Đoàn thanh niên Cộng sản Hồ Chí Minh (26/3/1931 - 26/3/2019)', '2019-03-26'),
(14, 'Nghỉ lễ Giỗ Tổ Hùng Vương', '2019-04-14'),
(15, 'dùng lịch mới', '2019-03-20');

-- --------------------------------------------------------

--
-- Table structure for table `tblmember`
--

CREATE TABLE `tblmember` (
  `Member_ID` smallint(5) UNSIGNED NOT NULL,
  `Member_Name` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `Member_ZaloID` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `Member_Email` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `Member_Choose` tinyint(3) UNSIGNED DEFAULT NULL,
  `Member_User` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `Member_Hash` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tblmember`
--

INSERT INTO `tblmember` (`Member_ID`, `Member_Name`, `Member_ZaloID`, `Member_Email`, `Member_Choose`, `Member_User`, `Member_Hash`) VALUES
(2, 'Nguyễn Thị Vi', '', 'nguyenvi10061996@gmail.com', 1, 'ntv', '$2y$12$ZHPsuJPOvGx3SF68PoDWduGHRl6xl2DumWsS7FeD.J0ULY9VVxr5S'),
(3, 'Đào Thị Phượng', '', 'pt909644@gmail.com', 1, 'dtp', '$2y$12$IshM7Wm2znC0KeAt5sM/Y.a5InziDmtM9Se.Xj4gFiJYzVfqIoW6u'),
(4, 'Nguyễn Tuấn Anh', '5499104223938322963', 'nguyentuananh01108@quangninh.gov.vn', 1, 'ntanh', '$2y$12$.k4wg5rFflovfMIRu44bI.P.0MdVyYwvqq/y3WzGkUe2OH/bQWLiW'),
(7, 'Vũ Thái Ninh', '5140918004426055963', 'vuthaininh@gmail.com', 1, 'vuthaininh', '$2y$12$.imV04YHiAMFzwQI0tzSSesFsoTZCvxVx9wdcACSciADbnkJDm4s.'),
(8, 'Nguyễn Hải Hà', '0906979888', 'nguyenhaiha@quangninh.gov.vn', 1, 'nguyenhaiha', '$2y$12$ykWhYfqd9TzpWXiEnPGI2eChE4ddsllOAR6Wx2184CUiT2TZmOf1q'),
(9, 'Phạm Thị Hồng Liên', '0942682800', 'phamthihonglien@quangninh.gov.vn', 1, 'phamthihonglien', '$2y$12$lduVItrTJrMXGKxtOsXfkecARYWA.f.WRmtKu3lPicM6PaTlBIKte'),
(10, 'Nguyễn Thị Vân Huyền', '0917386396', 'nguyenthivanhuyen@quangninh.gov.vn', 1, 'nguyenthivanhuyen', '$2y$12$1aQ4UGJLoqbcjgFW2NG2eOQtOxEZvw1.f9m0mD4NmrgRQhbZvCzhG'),
(11, 'Phạm Thị Trang', '0834130448', 'phamthitrang@quangninh.gov.vn', 1, 'phamthitrang', '$2y$12$YeiwWDsQ2FexwZXt/Vkn5e3.Tfjnlgecb3VlPa2DlTSeF./0/IBEe'),
(14, 'Phạm Thị Minh Hồng', '', 'phamthiminhhong@quangninh.gov.vn', 1, 'phamthiminhhong', '$2y$12$Fq2WIiF1cp.uesEoA/LEm.PxFohjP2XrANVqGvohNv8R82rWPebqC'),
(15, 'Đoàn Thị Thu Huyền', '', 'doanthithuhuyen@quangninh.gov.vn', 1, 'doanthithuhuyen', '$2y$12$1sJsKmVTNKKYn6C0weE7qeQTHoPvWA3Z1r3k/OfJQOHE8y3O7iRAS'),
(16, 'Nguyễn Minh Hiền', '', 'nguyenminhhien@quangninh.gov.vn', 1, 'nguyenminhhien', '$2y$12$OA0I5jawxGYf93QpsuWXDeN39RCP.4uX3p16oE0kyQ7aKpo4eYity'),
(17, 'Lê Thị Thúy', '', 'lethithuy@quangninh.gov.vn', 1, 'lethithuy', '$2y$12$hxpdUP4JqiOuCb8G4f3PkOdc07a8CRjvzlWal67Hf6efAW/JqKjii'),
(18, 'Phạm Văn Hạnh', '', 'phamvanhanh@quangninh.gov.vn', 1, 'phamvanhanh', '$2y$12$QW2TOWzR09HW9Oiqc91PxemsmO9TDUvSYDEXNMxdgANMRamA8GVK6'),
(19, 'Vũ Tiến Đạt', '', 'vutiendat@quangninh.gov.vn', 1, 'vutiendat', '$2y$12$CpBakq7SgfgzvyJOELWDTOPer251ev8dhUMhSWN.P/KO1Z1tL3mHe'),
(20, 'Phạm Quốc Tiến', '', 'phamquoctien@quangninh.gov.vn', 1, 'phamquoctien', '$2y$12$w9bg/K/6hkrcib3yVA4GBuvbx1UUXJUrM1NWNLEkSrtsrpkTZkP3u'),
(21, 'Vũ Hồng Hải', '', 'vuhonghai@quangninh.gov.vn', 1, 'vuhonghai', '$2y$12$qJaISrzBv/MfZINDX/lUgOCUvaM80N2jpmFl7S72j3wT5W8CFCq0S'),
(22, 'Hồ Văn Hảo', '', 'hovanhao@quangninh.gov.vn', 1, 'hovanhao', '$2y$12$8sZGD8GvvoDoqajOU4mJp.XohYxXq8Cgw0ozmJ94wQQ5AZrUyKWYm'),
(23, 'Trần Mạnh Cường', '', 'tranmanhcuong@quangninh.gov.vn', 1, 'tranmanhcuong', '$2y$12$.dLXZ8x8xILR8x6qJkPW5.k83e1m/3tXDESCM7Db5jTqGdpevGZ3.'),
(24, 'Nguyễn Gia Khánh', '', 'nguyengiakhanh@quangninh.gov.vn', 1, 'nguyengiakhanh', '$2y$12$DV2rc.1Tv79oUIDeQi8.pOHIHFSgR7uQlNLqMIof2ADwMLtCaSvOu'),
(25, 'Trần Hồng Ngọc', '', 'tranhongngoc@quangninh.gov.vn', 1, 'tranhongngoc', '$2y$12$K5Cn1BV5Txn.NHjqW6gPW.yv0NPBywjnQLlLgAGDE.//Cq0GSMg6K'),
(26, 'Nguyễn Xuân Thành', '', 'nguyenxuanthanh@quangninh.gov.vn', 1, 'nguyenxuanthanh', '$2y$12$TiJ9wkhYDvysEeWGWIwKG.ss9ZwJSC20n..x2UsxLwF3Uk5IRT6sC'),
(27, 'Đoàn Văn Dũng', '', 'doanvandung@quangninh.gov.vn', 1, 'doanvandung', '$2y$12$bP7XGCpeel4F1f3Fk/qQL.keSwmlmLUSIQ6MVowpMt/njOyQjYV3.'),
(28, 'Hoàng Hồng Quân', '', 'hoanghongquan@quangninh.gov.vn', 1, 'hoanghongquan', '$2y$12$eumCeeDL/0EWAvkyEWARweAvuW2uM4s4Mer0D3EzyQYH2BiGRKrKK'),
(29, 'Phùng Thế Phương', '', 'phungthephuong@quangninh.gov.vn', 1, 'phungthephuong', '$2y$12$NFKwoNkcd.MV70leaXnEguTMkStWv4Uo2He6I9.zrS6wDNHZghKJS');

-- --------------------------------------------------------

--
-- Table structure for table `tblmember_schedule`
--

CREATE TABLE `tblmember_schedule` (
  `Schedule_ID` int(10) UNSIGNED NOT NULL,
  `Member_ID` tinyint(3) UNSIGNED NOT NULL,
  `Notified` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tblmember_schedule`
--

INSERT INTO `tblmember_schedule` (`Schedule_ID`, `Member_ID`, `Notified`) VALUES
(112, 7, 0),
(114, 7, 0),
(115, 4, 0),
(116, 2, 1),
(116, 4, 1),
(117, 7, 1),
(118, 7, 1),
(118, 8, 1),
(119, 7, 0),
(119, 8, 1),
(119, 9, 1),
(121, 7, 1),
(121, 10, 1),
(121, 11, 1),
(122, 7, 1),
(122, 10, 1),
(122, 11, 1),
(125, 7, 0),
(125, 8, 0),
(125, 9, 0),
(125, 10, 0),
(125, 11, 0),
(127, 7, 1),
(127, 8, 1),
(127, 11, 1),
(120, 7, 0),
(120, 8, 0),
(120, 10, 0),
(120, 11, 0),
(128, 7, 1),
(128, 11, 1),
(129, 7, 0),
(130, 7, 1),
(126, 7, 0),
(126, 10, 0),
(126, 11, 0),
(134, 14, 0),
(133, 24, 0),
(133, 25, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblnotification`
--

CREATE TABLE `tblnotification` (
  `Noti_ID` int(10) UNSIGNED NOT NULL,
  `Noti_Content` text COLLATE utf8mb4_vietnamese_ci,
  `Noti_IsShow` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tblnotification`
--

INSERT INTO `tblnotification` (`Noti_ID`, `Noti_Content`, `Noti_IsShow`) VALUES
(19, '<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p style=\"text-align:center\"><strong>TH&Ocirc;NG B&Aacute;O</strong></p>\r\n\r\n<p style=\"text-align:justify\">Từ ng&agrave;y 04/3 đến hết ng&agrave;y 21/3/2018, Đ/c Hạnh - PP.NCUDPT đi nghiệm thu, khảo s&aacute;t hệ thống hội nghị truyền h&igrave;nh trực tuyến tại c&aacute;c cơ quan, đơn vị, địa phương trong tỉnh.</p>\r\n\r\n<p style=\"text-align:justify\">Từ ng&agrave;y 11/3/2019 - 13/3/2019 Đ/c Nguyễn Gia Kh&aacute;nh - NV P.QLTTTHDL nghỉ ph&eacute;p.</p>\r\n', -1);

-- --------------------------------------------------------

--
-- Table structure for table `tblschedule`
--

CREATE TABLE `tblschedule` (
  `Schedule_ID` int(10) UNSIGNED NOT NULL,
  `Schedule_Content` text COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `Schedule_Date` date NOT NULL,
  `Schedule_Time` time NOT NULL,
  `Schedule_DepartmentID` tinyint(4) NOT NULL,
  `Schedule_Show` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tblschedule`
--

INSERT INTO `tblschedule` (`Schedule_ID`, `Schedule_Content`, `Schedule_Date`, `Schedule_Time`, `Schedule_DepartmentID`, `Schedule_Show`) VALUES
(112, '<p>test</p>\r\n\r\n<p>lịch c&ocirc;ng t&aacute;c</p>\r\n', '2019-03-05', '14:00:00', 1, 1),
(113, '<p>test lịch ng&agrave;y tiếp theo</p>\r\n', '2019-03-06', '00:00:00', 1, 1),
(114, '<p>lịch bổ sung</p>\r\n', '2019-03-05', '11:00:00', 1, 1),
(115, '<p>test lịch c&ocirc;ng t&aacute;c</p>\r\n', '2019-03-05', '16:00:00', 1, 1),
(116, '<p><strong><span style=\"color:red\">0:00</span></strong><span style=\"font-size:14px\"><span style=\"color:#333333\"><span style=\"font-family:Roboto,sans-serif\"><span style=\"background-color:#f7fcff\">&nbsp;test lịch ng&agrave;y tiếp theo</span></span></span></span></p>\r\n', '2019-03-06', '01:30:00', 1, 1),
(117, '<p>V&igrave; em kh&ocirc;ng c&oacute; t&agrave;i khoản email theo domain của tỉnh n&ecirc;n em gửi tạm sang email n&agrave;y của anh ạ. Nếu anh nhận được email n&agrave;y th&igrave; nhớ nhắn lại cho em tr&ecirc;n zalo anh nh&eacute; <img alt=\"laugh\" src=\"http://lctstt.qnict.vn/js/ckeditor/plugins/smiley/images/teeth_smile.png\" style=\"height:23px; width:23px\" title=\"laugh\" /></p>\r\n', '2019-03-06', '07:00:00', 1, 0),
(118, '<p>Triển khai phần mềm lịch c&ocirc;ng t&aacute;c tại Hải Ph&ograve;ng (Đ/c H&agrave; - PGĐ phụ tr&aacute;ch, P.QLTTTHDL, P.NCUDPT)</p>\r\n', '2019-03-14', '10:00:00', 1, 1),
(119, '<p>L&agrave;m việc với Trường đ&agrave;o tạo c&aacute;n bộ Nguyễn Văn Cừ về triển khai c&ocirc;ng t&aacute;c đ&agrave;o tạo lĩnh vực Th&ocirc;ng tin Truyền th&ocirc;ng theo Quyết định số 481/QĐ-UBND ng&agrave;y 31/01/2019 của UBND tỉnh tại Ph&ograve;ng họp Sở (Đ/c H&agrave; - PGĐ phụ tr&aacute;ch, Đ/c Ninh - TP.NCUDPT, Đ/c Li&ecirc;n - PP.NCUDPT).</p>\r\n', '2019-03-15', '08:00:00', 1, 1),
(120, '<p>Tham gia Lễ khai giảng lớp bồi dưỡng ch&iacute;nh trị cho đảng vi&ecirc;n mới, kho&aacute; I năm 2019 tại Hội trường B, tầng 1, trụ sở Li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - Trung t&acirc;m CNTT&amp;TT; Trang phục: &Aacute;o d&agrave;i truyền thống)</p>\r\n', '2019-03-18', '07:30:00', 1, 1),
(121, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-19', '07:30:00', 1, 1),
(122, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-20', '07:30:00', 1, 1),
(123, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-21', '07:30:00', 1, 1),
(124, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-22', '07:30:00', 1, 1),
(125, '<p>Kiểm tra nhận email phần mềm quản l&yacute; lịch cơ quan</p>\r\n', '2019-03-14', '11:50:00', 1, 1),
(126, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-24', '07:30:00', 1, 1),
(127, '<p>Test lịch c&ocirc;ng t&aacute;c</p>\r\n', '2019-03-18', '10:00:00', 1, 1),
(128, '<p>test lịch c&ocirc;ng t&aacute;c</p>\r\n', '2019-03-19', '07:30:00', 1, 1),
(129, '<p>test lịch</p>\r\n', '2019-03-17', '11:00:00', 1, 1),
(130, '<p>test lần 2 (gmail)</p>\r\n', '2019-03-18', '11:00:00', 1, 1),
(131, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-23', '07:30:00', 1, 1),
(132, '<p>Tham gia lớp bồi dưỡng l&iacute; luận ch&iacute;nh trị cho đảng vi&ecirc;n mới tại Hội trường B, tầng 1, trụ sở li&ecirc;n cơ quan số 4 (Đ/c V&acirc;n Huyền - PP.NCUDPT)</p>\r\n', '2019-03-25', '07:30:00', 1, 1),
(133, '<p>Hỗ trợ đ&agrave;o tạo tập huấn Cổng th&ocirc;ng tin điện tử (Đ/c Kh&aacute;nh, Đ/c Ngọc - QLTTTHDL)</p>\r\n', '2019-03-20', '08:00:00', 1, 1),
(134, '<p>Nhận b&agrave;n giao hồ sơ, t&agrave;i liệu dự &aacute;n CQĐT tại Ban CQĐT (Đ/c Hồng - VC P.NCUDPT)</p>\r\n', '2019-03-19', '08:30:00', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  ADD PRIMARY KEY (`Department_ID`);

--
-- Indexes for table `tblevent`
--
ALTER TABLE `tblevent`
  ADD PRIMARY KEY (`Event_ID`);

--
-- Indexes for table `tblmember`
--
ALTER TABLE `tblmember`
  ADD PRIMARY KEY (`Member_ID`);

--
-- Indexes for table `tblnotification`
--
ALTER TABLE `tblnotification`
  ADD PRIMARY KEY (`Noti_ID`);

--
-- Indexes for table `tblschedule`
--
ALTER TABLE `tblschedule`
  ADD PRIMARY KEY (`Schedule_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  MODIFY `Department_ID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblevent`
--
ALTER TABLE `tblevent`
  MODIFY `Event_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblmember`
--
ALTER TABLE `tblmember`
  MODIFY `Member_ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tblnotification`
--
ALTER TABLE `tblnotification`
  MODIFY `Noti_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `Schedule_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
