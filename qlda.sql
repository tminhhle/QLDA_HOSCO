-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 03, 2025 lúc 04:27 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlda`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `congviec`
--

CREATE TABLE `congviec` (
  `MaCV` varchar(10) NOT NULL,
  `TenCV` varchar(100) DEFAULT NULL,
  `NgayBDDuKien` datetime DEFAULT NULL,
  `NgayKTDuKien` datetime DEFAULT NULL,
  `NgayBDThucTe` datetime DEFAULT NULL,
  `NgayKTThucTe` datetime DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT NULL,
  `MaNguoiTao` varchar(50) DEFAULT NULL,
  `MaDA` varchar(50) DEFAULT NULL,
  `MaNguoiThucHien` varchar(50) DEFAULT NULL,
  `MoTa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `congviec`
--

INSERT INTO `congviec` (`MaCV`, `TenCV`, `NgayBDDuKien`, `NgayKTDuKien`, `NgayBDThucTe`, `NgayKTThucTe`, `TrangThai`, `MaNguoiTao`, `MaDA`, `MaNguoiThucHien`, `MoTa`) VALUES
('CV03', 'Viết tài liệu', '2024-02-01 00:00:00', '2024-02-10 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Đang tiến hành', 'ND02', 'DA02', 'ND04', 'vdxcs'),
('CV04', 'Lập trình backend', '2024-03-01 00:00:00', '2024-03-20 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Chưa bắt đầu', 'ND02', 'DA02', 'ND05', 'csxa'),
('CV05', 'Kiểm thử hệ thống', '2024-04-01 00:00:00', '2024-04-10 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Đang tiến hành', 'ND02', 'DA03', 'ND06', 'vSZC'),
('CV06', 'Triển khai server', '2024-05-01 00:00:00', '2024-05-15 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Hoàn thành', 'ND07', 'DA04', 'ND07', 'csxs'),
('CV07', 'Thu thập phản hồi', '2024-06-01 00:00:00', '2024-06-10 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Chưa bắt đầu', 'ND04', 'DA05', 'ND08', 'bxvs'),
('CV09', 'Cập nhật tài liệu', '2024-07-01 00:00:00', '2024-07-15 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Chưa bắt đầu', 'ND10', 'DA02', 'ND10', 'kkk');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `duan`
--

CREATE TABLE `duan` (
  `MaDA` varchar(50) NOT NULL,
  `TenDA` varchar(100) DEFAULT NULL,
  `NgayBDDuKien` datetime DEFAULT NULL,
  `NgayKTDuKien` datetime DEFAULT NULL,
  `NgayBDThucTe` datetime DEFAULT NULL,
  `NgayKTThucTe` datetime DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `MaNguoiTao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `duan`
--

INSERT INTO `duan` (`MaDA`, `TenDA`, `NgayBDDuKien`, `NgayKTDuKien`, `NgayBDThucTe`, `NgayKTThucTe`, `TrangThai`, `MoTa`, `MaNguoiTao`) VALUES
('DA02', 'Dự án Beta', '2024-03-01 00:00:00', '2024-09-30 00:00:00', '2024-03-03 00:00:00', NULL, 'Đang tiến hành', 'Dự án thử nghiệm Beta', 'ND03'),
('DA03', 'Dự án Gamma', '2024-05-01 00:00:00', '2024-10-15 00:00:00', NULL, NULL, 'Chưa bắt đầu', 'Dự án mở rộng Gamma', 'ND03'),
('DA04', 'Dự án Delta', '2023-11-01 00:00:00', '2024-04-30 00:00:00', '2023-11-02 00:00:00', '2024-04-25 00:00:00', 'Hoàn thành', 'Phát triển phần mềm Delta', 'ND02'),
('DA05', 'Dự án Epsilon', '2024-02-15 00:00:00', '2024-07-30 00:00:00', '2024-02-16 00:00:00', NULL, 'Chưa bắt đầu', 'Nâng cấp hệ thống Epsilon', 'ND03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaNguoiDung` varchar(50) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `ChucVu` varchar(50) DEFAULT NULL,
  `MaPhongBan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`MaNguoiDung`, `HoTen`, `Password`, `GioiTinh`, `NgaySinh`, `SDT`, `Email`, `DiaChi`, `ChucVu`, `MaPhongBan`) VALUES
('ND02', 'Trần Thị B', '123456', 'Nữ', '1992-02-15', '0922222222', 'b@example.com', 'TP.HCM', 'Trưởng phòng', 'PB01'),
('ND03', 'Lê Văn C', '123456', 'Nam', '1988-03-10', '0933333333', 'c@example.com', 'Đà Nẵng', 'Nhân viên', 'PB02'),
('ND04', 'Phạm Thị D', '123456', 'Nữ', '1995-04-20', '0944444444', 'd@example.com', 'Hải Phòng', 'Trưởng phòng', 'PB02'),
('ND05', 'Đỗ Văn E', '123456', 'Nam', '1991-05-05', '0955555555', 'e@example.com', 'Cần Thơ', 'Nhân viên', 'PB01'),
('ND06', 'Bùi Thị F', '123456', 'Nữ', '1987-06-06', '0966666666', 'f@example.com', 'Huế', 'Nhân viên', 'PB03'),
('ND07', 'Ngô Văn G', '123456', 'Nam', '1989-07-07', '0977777777', 'g@example.com', 'Nghệ An', 'Trưởng phòng', 'PB03'),
('ND08', 'Vũ Thị H', '123456', 'Nữ', '1993-08-08', '0988888888', 'h@example.com', 'Quảng Ninh', 'Nhân viên', 'PB01'),
('ND09', 'Lý Văn I', '123456', 'Nam', '1994-09-09', '0999999999', 'i@example.com', 'Bình Dương', 'Nhân viên', 'PB02'),
('ND10', 'Trịnh Thị K', '123456', 'Nữ', '1996-10-10', '0900000000', 'k@example.com', 'Lâm Đồng', 'Trưởng phòng', 'PB04'),
('ND11', 'Trần Thị M', '123456', 'Nữ', '2000-11-17', '0998532014', 'm@example.com', 'Quảng Ngãi', 'Nhân viên', 'PB04'),
('ND13', 'Minh', '123456', 'Nữ', '2003-10-07', '1254610', 'lethihongminh107@gmail.com', 'số ', 'Admin', 'PB02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidungduan`
--

CREATE TABLE `nguoidungduan` (
  `MaDA` varchar(50) NOT NULL,
  `MaNguoiThamGia` varchar(50) NOT NULL,
  `VaiTro` varchar(50) DEFAULT NULL,
  `NgayThamGiaDuAn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidungduan`
--

INSERT INTO `nguoidungduan` (`MaDA`, `MaNguoiThamGia`, `VaiTro`, `NgayThamGiaDuAn`) VALUES
('DA02', 'ND04', 'Thành viên', '2024-03-01 00:00:00'),
('DA02', 'ND05', 'Quản lý', '2024-03-01 00:00:00'),
('DA02', 'ND10', 'Thành viên', '2023-09-01 00:00:00'),
('DA03', 'ND04', 'Quản lý', '2024-06-01 00:00:00'),
('DA03', 'ND06', 'Thành viên', '2024-05-01 00:00:00'),
('DA04', 'ND07', 'Quản lý', '2023-11-01 00:00:00'),
('DA05', 'ND08', 'Thành viên', '2024-02-15 00:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongban`
--

CREATE TABLE `phongban` (
  `MaPhongBan` varchar(50) NOT NULL,
  `TenPhongBan` varchar(100) DEFAULT NULL,
  `MoTa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phongban`
--

INSERT INTO `phongban` (`MaPhongBan`, `TenPhongBan`, `MoTa`) VALUES
('PB01', 'Phòng Kỹ thuật', 'Phụ trách nghiên cứu, phát triển và duy trì các sản phẩm, công nghệ của công ty.'),
('PB02', 'Phòng Hành chính Nhân sự', 'Quản lý công tác tuyển dụng, đào tạo, chính sách nhân sự và hỗ trợ công tác hành chính.'),
('PB03', 'Phòng Kinh doanh', 'Phụ trách việc tiếp cận, đàm phán và phát triển mối quan hệ với khách hàng, tăng trưởng doanh thu.'),
('PB04', 'Phòng Tài chính kế toán', 'Quản lý tài chính, kế toán, kiểm soát chi phí và báo cáo tài chính của công ty.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongke`
--

CREATE TABLE `thongke` (
  `MaThongKe` varchar(50) NOT NULL,
  `TenThongKe` varchar(100) DEFAULT NULL,
  `NoiDung` text DEFAULT NULL,
  `MaNguoiTao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thongke`
--

INSERT INTO `thongke` (`MaThongKe`, `TenThongKe`, `NoiDung`, `MaNguoiTao`) VALUES
('TK01', 'Tổng quan tiến độ DA01', 'Đã hoàn thành 50% công việc', 'ND02'),
('TK02', 'Tiến độ DA02', 'Đang triển khai các module chính', 'ND04'),
('TK03', 'Hiệu suất ND03', 'Hoàn thành đúng hạn 5 công việc', 'ND07'),
('TK04', 'Thống kê DA04', 'Dự án đã hoàn thành sớm 5 ngày', 'ND02'),
('TK05', 'Báo cáo DA05', 'Tiến độ đúng như kế hoạch', 'ND04'),
('TK06', 'Báo cáo phòng PB01', 'Tăng năng suất 10% so với tháng trước', 'ND02'),
('TK07', 'Tổng hợp công việc', 'Cập nhật công việc toàn phòng', 'ND02'),
('TK08', 'Hiệu suất DA07', 'Đạt 95% tiến độ đề ra', 'ND10'),
('TK09', 'Thống kê quý 1', 'Tổng kết các dự án Q1', 'ND04'),
('TK10', 'Báo cáo chi tiết ND05', 'Đạt 90% KPI cá nhân', 'ND07');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `congviec`
--
ALTER TABLE `congviec`
  ADD PRIMARY KEY (`MaCV`),
  ADD KEY `MaNguoiTao` (`MaNguoiTao`),
  ADD KEY `MaNguoiThucHien` (`MaNguoiThucHien`),
  ADD KEY `MaDA` (`MaDA`);

--
-- Chỉ mục cho bảng `duan`
--
ALTER TABLE `duan`
  ADD PRIMARY KEY (`MaDA`),
  ADD KEY `MaNguoiTao` (`MaNguoiTao`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaNguoiDung`),
  ADD KEY `MaPhongBan` (`MaPhongBan`);

--
-- Chỉ mục cho bảng `nguoidungduan`
--
ALTER TABLE `nguoidungduan`
  ADD PRIMARY KEY (`MaDA`,`MaNguoiThamGia`),
  ADD KEY `MaNguoiThamGia` (`MaNguoiThamGia`);

--
-- Chỉ mục cho bảng `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`MaPhongBan`);

--
-- Chỉ mục cho bảng `thongke`
--
ALTER TABLE `thongke`
  ADD PRIMARY KEY (`MaThongKe`),
  ADD KEY `MaNguoiTao` (`MaNguoiTao`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `congviec`
--
ALTER TABLE `congviec`
  ADD CONSTRAINT `congviec_ibfk_1` FOREIGN KEY (`MaNguoiTao`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `congviec_ibfk_2` FOREIGN KEY (`MaNguoiThucHien`) REFERENCES `nguoidung` (`MaNguoiDung`),
  ADD CONSTRAINT `congviec_ibfk_3` FOREIGN KEY (`MaDA`) REFERENCES `duan` (`MaDA`);

--
-- Các ràng buộc cho bảng `duan`
--
ALTER TABLE `duan`
  ADD CONSTRAINT `duan_ibfk_1` FOREIGN KEY (`MaNguoiTao`) REFERENCES `nguoidung` (`MaNguoiDung`);

--
-- Các ràng buộc cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `nguoidung_ibfk_1` FOREIGN KEY (`MaPhongBan`) REFERENCES `phongban` (`MaPhongBan`);

--
-- Các ràng buộc cho bảng `nguoidungduan`
--
ALTER TABLE `nguoidungduan`
  ADD CONSTRAINT `nguoidungduan_ibfk_1` FOREIGN KEY (`MaDA`) REFERENCES `duan` (`MaDA`),
  ADD CONSTRAINT `nguoidungduan_ibfk_2` FOREIGN KEY (`MaNguoiThamGia`) REFERENCES `nguoidung` (`MaNguoiDung`);

--
-- Các ràng buộc cho bảng `thongke`
--
ALTER TABLE `thongke`
  ADD CONSTRAINT `thongke_ibfk_1` FOREIGN KEY (`MaNguoiTao`) REFERENCES `nguoidung` (`MaNguoiDung`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
