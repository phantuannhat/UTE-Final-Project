-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 25, 2022 lúc 04:55 PM
-- Phiên bản máy phục vụ: 10.1.31-MariaDB
-- Phiên bản PHP: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `du_an`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `phone` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `uid`, `username`, `password`, `name`, `birthday`, `phone`, `email`, `address`) VALUES
(1, '302012234', 'admin', 'admin', 'Nguyễn Văn Trúc', '2003-07-05', '0898169249', 'vnatruc@gmail.com', 'Điện Bàn, Quảng Nam'),
(2, '453234', 'mailco', '5720019', 'Nguyễn Văn B', '2022-03-09', '0232421222', 'csad@gmail.com', 'dmasmds'),
(3, '4102', 'ABC', 'abc', 'Nguyễn Văn C', '2022-03-09', '0801839232', 'va@gmail.com', 'ABC');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_activities`
--

CREATE TABLE `log_activities` (
  `id` int(11) NOT NULL,
  `trash_can_id` int(11) NOT NULL,
  `weight` float NOT NULL,
  `staffid` int(11) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `log_activities`
--

INSERT INTO `log_activities` (`id`, `trash_can_id`, `weight`, `staffid`, `datetime`) VALUES
(1, 50, 3, 302012234, '2022-03-06 17:06:56'),
(2, 49, 7, 302012234, '2022-03-06 18:49:19'),
(3, 49, 4, 302012234, '2022-03-06 18:49:35'),
(4, 49, 4, 302012234, '0000-00-00 00:00:00'),
(5, 49, 4, 302012234, '2022-03-06 20:58:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `super_admin`
--

CREATE TABLE `super_admin` (
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `super_admin`
--

INSERT INTO `super_admin` (`username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trash_can`
--

CREATE TABLE `trash_can` (
  `id` int(11) NOT NULL,
  `location` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `weight` float NOT NULL DEFAULT '0',
  `garbagepercent` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trash_can`
--

INSERT INTO `trash_can` (`id`, `location`, `weight`, `garbagepercent`, `token`) VALUES
(49, '16.078203, 108.212051', 4, '90', 'ZOfOEj3ReEDNqRZRerZQ'),
(50, '16.078724,108.214448', 3, '40', 'zLKBstiL8AgK2zlawAo9'),
(57, '16.078316,108.213146', 6, '100', 'hdhfd'),
(58, '16.078067,108.2158232', 2, '29', 'á'),
(59, '16.075167, 108.214364', 3, '10', '43453dfdfzs');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `trash_can_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dayofbirth` date NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `isolatedday` date NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ward` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `district` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`trash_can_id`, `name`, `dayofbirth`, `phone`, `isolatedday`, `email`, `address`, `ward`, `district`, `city`) VALUES
(49, 'Nguyễn Văn A', '2022-03-10', '0327162372', '2022-03-12', 'h@gmail.com', 'ádsads', 'Phường Thanh Hà', 'thành phố hội an', 'quảng nam'),
(50, 'Nguyễn Văn B', '2022-03-16', '0434232432', '2022-03-03', 'a@gmail.com', '21 Hoang Hoa Tham', 'Phường Phúc Xá', 'Quận Ba Đình', ' Hà Nội'),
(57, 'Nguyễn Văn C', '2022-03-15', '0332342323', '2022-03-05', 'a323@gmail.com', '23 aa', 'Phường Trúc Bạch', 'Quận Ba Đình', 'Hà Nội'),
(59, 'Nguyễn Văn D', '2022-03-03', '0982137422', '2022-03-09', 'fgfg@gmail.com', 'Thoonnn abc', 'xã Điện Minh', 'Huyện Điện Bàn', 'Quảng Nam');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `log_activities`
--
ALTER TABLE `log_activities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `trash_can`
--
ALTER TABLE `trash_can`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`trash_can_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `log_activities`
--
ALTER TABLE `log_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `trash_can`
--
ALTER TABLE `trash_can`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`trash_can_id`) REFERENCES `trash_can` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
