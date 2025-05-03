<?php
include 'db.php';
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';

// Lấy danh sách phòng ban
$phongbanList = [];
$sql_pb = "SELECT MaPhongBan, TenPhongBan FROM phongban";
$result_pb = $conn->query($sql_pb);
if ($result_pb && $result_pb->num_rows > 0) {
    while ($row = $result_pb->fetch_assoc()) {
        $phongbanList[] = $row;
    }
}

// Kiểm tra mã người dùng
if (!isset($_GET['MaNguoiDung'])) {
    echo "Mã người dùng không hợp lệ!";
    exit;
}
$maND = $_GET['MaNguoiDung'];

// Lấy thông tin người dùng
$sql = "SELECT * FROM nguoidung WHERE MaNguoiDung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maND);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "Không tìm thấy người dùng!";
    exit;
}
$nguoidung = $result->fetch_assoc();

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoTen = $_POST['HoTen'];
    $matKhau = $_POST['Password'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $sdt = $_POST['SDT'];
    $email = $_POST['Email'];
    $diaChi = $_POST['DiaChi'];
    $chucVu = $_POST['ChucVu'];
    $maPhongBan = $_POST['MaPhongBan'];

    $sql_update = "UPDATE nguoidung 
                   SET HoTen=?, Password=?, GioiTinh=?, NgaySinh=?, SDT=?, Email=?, DiaChi=?, ChucVu=?, MaPhongBan=? 
                   WHERE MaNguoiDung=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssssss", $hoTen, $matKhau, $gioiTinh, $ngaySinh, $sdt, $email, $diaChi, $chucVu, $maPhongBan, $maND);

    if ($stmt_update->execute()) {
        echo "<script>
            alert('✅ Sửa người dùng thành công!');
            window.location.href = 'quan-ly-nguoi-dung.php';
        </script>";
        exit();
    } else {
        echo "❌ Cập nhật thất bại!";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Người Dùng</title>
    <link rel="stylesheet" href="../CSS/sua-nguoi-dung.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
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
    <section class="edit-user">
        <h1>SỬA NGƯỜI DÙNG</h1>
        <form method="post">
            <label for="name">Họ tên</label>
            <input type="text" id="name" name="HoTen" value="<?= htmlspecialchars($nguoidung['HoTen']) ?>" required>

            <label for="password">Mật khẩu</label>
            <input type="text" id="password" name="Password" value="<?= htmlspecialchars($nguoidung['Password']) ?>" required>

            <label for="gender">Giới tính</label>
            <select id="gender" name="GioiTinh" required>
                <option value="">Chọn giới tính</option>
                <option value="Nam" <?= $nguoidung['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                <option value="Nữ" <?= $nguoidung['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                <option value="Khác" <?= $nguoidung['GioiTinh'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
            </select>

            <label for="birthdate">Ngày sinh</label>
            <input type="date" id="birthdate" name="NgaySinh" value="<?= $nguoidung['NgaySinh'] ?>" required>

            <label for="phone">SDT</label>
            <input type="text" id="phone" name="SDT" value="<?= htmlspecialchars($nguoidung['SDT']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="Email" value="<?= htmlspecialchars($nguoidung['Email']) ?>" required>

            <label for="address">Địa chỉ</label>
            <input type="text" id="address" name="DiaChi" value="<?= htmlspecialchars($nguoidung['DiaChi']) ?>" required>

            <label for="role">Chức vụ</label>
            <select id="role" name="ChucVu" required>
                <option value="">Chọn chức vụ</option>
                <option value="Nhân viên" <?= $nguoidung['ChucVu'] == 'Nhân viên' ? 'selected' : '' ?>>Nhân viên</option>
                <option value="Trưởng phòng" <?= $nguoidung['ChucVu'] == 'Trưởng phòng' ? 'selected' : '' ?>>Trưởng phòng</option>
                <option value="Admin" <?= $nguoidung['ChucVu'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Giám đốc" <?= $nguoidung['ChucVu'] == 'Giám đốc' ? 'selected' : '' ?>>Giám đốc</option>
            </select>

            <label>Phòng ban:</label>
        <select name="MaPhongBan" required>
            <option value="">-- Chọn phòng ban --</option>
            <?php foreach ($phongbanList as $pb): ?>
                <option value="<?= $pb['MaPhongBan'] ?>" <?= $nguoidung['MaPhongBan'] == $pb['MaPhongBan'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($pb['TenPhongBan']) ?>
                </option>
            <?php endforeach; ?>
        </select>
            <button type="submit">LƯU</button>
        </form>
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


