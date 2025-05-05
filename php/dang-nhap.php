<?php
session_start();
include 'db.php'; // file kết nối database

// Nếu có dữ liệu gửi POST (khi form gửi bằng fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM nguoidung WHERE MaNguoiDung = '$username' AND Password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['MaNguoiDung'] = $row['MaNguoiDung'];
        $_SESSION['HoTen'] = $row['HoTen'];
        $_SESSION['ChucVu'] = $row['ChucVu'];
        echo "success";
    } else {
        echo "fail";
    }
    exit; // 
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập - Quản lý dự án HOSCO Việt Nam</title>
  <link rel="stylesheet" href="../CSS/dang-nhap.css">
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <img src="../img/logo-footer.png" alt="HOSCO Logo" id="logo" />
      <h1>CÔNG TY CỔ PHẦN HOSCO VIỆT NAM</h1>
      <img class="illustration" src="../img/pngegg.png" alt="Minh họa" id="minhhoa"/>
    </div>
    <div class="right-panel">
      <h2>ĐĂNG NHẬP</h2>
      <form id="loginForm" method="POST">
        <div class="form-group">
          <label for="username">Tên đăng nhập</label>
          <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required />
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu</label>
          <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required />
        </div>
        <button type="submit" class="btn-login">ĐĂNG NHẬP</button>
      </form>
    </div>
  </div>
  <script>
    document.getElementById("loginForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch(window.location.href, { // Gửi đến chính file hiện tại
        method: "POST",
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        if (data.trim() === "success") {
          window.location.href = "trang-chu.php";
        } else {
          alert("Đăng nhập thất bại! Sai tên đăng nhập hoặc mật khẩu.");
        }
      })
      .catch(() => alert("Lỗi kết nối. Vui lòng thử lại."));
    });
  </script>
</body>
</html>
