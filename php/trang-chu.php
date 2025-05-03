<?php
session_start();
if (!isset($_SESSION['HoTen'])) {
    header("Location: dang-nhap.php"); // Nếu chưa đăng nhập thì chuyển về trang đăng nhập
    exit();
}
$hoten = $_SESSION['HoTen']; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <link rel="stylesheet" href="../CSS/trang-chu.css">
</head>
<body>
<header>
        <nav class="navbar">
            <a class="active" href="trang-chu.php">Trang chủ</a>
            <a href="quan-ly-du-an.php">Quản lý dự án</a>
            <a  href="quan-ly-cong-viec.php">Quản lý công việc</a>
            <a href="quan-ly-nguoi-dung.php">Quản lý người dùng</a>
            <a href="thong-ke.php">Thống kê</a>
            <div class="user-dropdown" onmouseover="showLogout()" onmouseout="hideLogout()">
    <div class="user-info">
    <i class="fa-regular fa-circle-user"></i><span><strong><?= htmlspecialchars($hoten) ?></strong></span>
    </div>
    <div class="logout-menu" id="logoutMenu">
        <a href="dang-xuat.php">Đăng xuất</a>
    </div>
</div>
        </nav>
    </header>

    <main>
        <section class="dashboard">
            <h1>HỆ THỐNG QUẢN LÝ DỰ ÁN</h1>
            <div class="cards">
                <div class="card">
                    <h2>Dự Án</h2>
                    <p>Quản lý tiến độ và thông tin dự án</p>
                    <a href="quan-ly-du-an.php">Xem chi tiết →</a>
                </div>
                <div class="card">
                    <h2>Công Việc</h2>
                    <p>Phân công, theo dõi và cập nhật tiến độ công việc</p>
                    <a href="quan-ly-cong-viec.php">Xem chi tiết →</a>
                </div>
                <div class="card">
                    <h2>Người Dùng</h2>
                    <p>Thêm, sửa, xem thông tin người dùng</p>
                    <a href="quan-ly-nguoi-dung.php">Xem chi tiết →</a>
                </div>
                <div class="card">
                    <h2>Thống Kê</h2>
                    <p>Xem báo cáo, biểu đồ tổng quan</p>
                    <a href="thong-ke.php">Xem chi tiết →</a>
                </div>
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
