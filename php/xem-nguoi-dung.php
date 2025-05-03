<?php
include 'db.php';
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';

if (!isset($_GET['MaNguoiDung'])) {
    echo "Không có mã người dùng được cung cấp.";
    exit;
}

$maNguoiDung = $_GET['MaNguoiDung'];

$sql = "SELECT nguoidung.*, phongban.TenPhongBan 
        FROM nguoidung 
        LEFT JOIN phongban ON nguoidung.MaPhongBan = phongban.MaPhongBan 
        WHERE MaNguoiDung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maNguoiDung);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy người dùng.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem Người Dùng</title>
    <link rel="stylesheet"  href= "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <link rel="stylesheet" href="../CSS/xem-nguoi-dung.css">
</head>
<body>
<header>
        <nav class="navbar">
            <a href="trang-chu.php">Trang chủ</a>
            <a  href="../php/quan-ly-du-an.php">Quản lý dự án</a>
            <a  href="quan-ly-cong-viec.php">Quản lý công việc</a>
            <a class="active" href="#">Quản lý người dùng</a>
            <a href="thong-ke.php">Thống kê</a>
            <div class="user-dropdown" onmouseover="showLogout()" onmouseout="hideLogout()">
    <div class="user-info">
    <i class="fa-regular fa-circle-user"></i><span><strong><?= htmlspecialchars($tenNguoiDung) ?></strong></span>
    </div>
    <div class="logout-menu" id="logoutMenu">
        <a href="dang-xuat.php">Đăng xuất</a>
    </div>
</div>
        </nav>
    </header>

    <div class="back-wrapper">
        <button class="back-btn" onclick="history.back()">← Quay lại</button>
    </div>

    <main>
        <section class="view-user">
            <h1>XEM NGƯỜI DÙNG</h1>
            <div class="user-details">
                <div class="detail"><label>Họ tên:</label> <span><?= htmlspecialchars($row['HoTen']) ?></span></div>
                <div class="detail"><label>Mật khẩu:</label> <span>******</span></div>
                <div class="detail"><label>Giới tính:</label> <span><?= htmlspecialchars($row['GioiTinh']) ?></span></div>
                <div class="detail"><label>Ngày sinh:</label> <span><?= date('d/m/Y', strtotime($row['NgaySinh'])) ?></span></div>
                <div class="detail"><label>SDT:</label> <span><?= htmlspecialchars($row['SDT']) ?></span></div>
                <div class="detail"><label>Email:</label> <span><?= htmlspecialchars($row['Email']) ?></span></div>
                <div class="detail"><label>Địa chỉ:</label> <span><?= htmlspecialchars($row['DiaChi']) ?></span></div>
                <div class="detail"><label>Chức vụ:</label> <span><?= htmlspecialchars($row['ChucVu']) ?></span></div>
                <div class="detail"><label>Phòng ban:</label> <span><?= htmlspecialchars($row['TenPhongBan']) ?></span></div>
            </div>
        </section>
    </main>
    <script>
    function showLogout() {
    document.getElementById("logoutMenu").style.display = "block";
}

function hideLogout() {
    document.getElementById("logoutMenu").style.display = "none";
}
      </script>
</body>
</html>
