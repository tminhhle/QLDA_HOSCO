<?php
include 'db.php';
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thống Kê</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
  <link rel="stylesheet" href="../CSS/thong-ke.css">
</head>
<body>
<header>
        <nav class="navbar">
            <a  href="trang-chu.php">Trang chủ</a>
            <a href="quan-ly-du-an.php">Quản lý dự án</a>
            <a  href="quan-ly-cong-viec.php">Quản lý công việc</a>
            <a href="quan-ly-nguoi-dung.php">Quản lý người dùng</a>
            <a class="active" href="thong-ke.php">Thống kê</a>
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

  <main>
    <section class="dashboard">
      <h1>THỐNG KÊ TỔNG QUAN</h1>
      <div class="cards">
        <div class="card">
          <h2>Tiến độ dự án</h2>
          <p>Xem tỷ lệ hoàn thành, tiến độ và tình trạng các dự án</p>
          <a href="thongke_tiendo.html">Xem chi tiết →</a>
        </div>
        <div class="card">
          <h2>Hiệu quả người dùng</h2>
          <p>Thống kê công việc, số lượng hoàn thành của từng người dùng</p>
          <a href="thongke_hieuqua.html">Xem chi tiết →</a>
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
