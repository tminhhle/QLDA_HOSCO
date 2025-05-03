<?php
include 'db.php'; // Kết nối CSDL
$thongBao = '';
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';
// Lấy danh sách phòng ban
$dsPhongBan = [];
$sqlPBList = "SELECT TenPhongBan FROM phongban";
$resultPBList = $conn->query($sqlPBList);
if ($resultPBList && $resultPBList->num_rows > 0) {
    while ($row = $resultPBList->fetch_assoc()) {
        $dsPhongBan[] = $row['TenPhongBan'];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hoTen = $_POST['name'];
    $matKhau = $_POST['password'];
    $gioiTinh = $_POST['gender'];
    $ngaySinh = $_POST['birthdate'];
    $sdt = $_POST['phone'];
    $email = $_POST['email'];
    $diaChi = $_POST['address'];
    $chucVu = $_POST['role'];
    $tenPhongBan = $_POST['department'];

    // Tìm mã phòng ban
    $sqlPB = "SELECT MaPhongBan FROM phongban WHERE TenPhongBan = ?";
    $stmtPB = $conn->prepare($sqlPB);
    $stmtPB->bind_param("s", $tenPhongBan);
    $stmtPB->execute();
    $resultPB = $stmtPB->get_result();

    if ($resultPB->num_rows === 0) {
        $thongBao = "❌ Phòng ban không tồn tại!";
    } else {
        $maPhongBan = $resultPB->fetch_assoc()['MaPhongBan'];

        // Tạo mã người dùng tự động
        $query = "SELECT MaNguoiDung FROM nguoidung ORDER BY MaNguoiDung DESC LIMIT 1";
        $result = $conn->query($query);
        $newCode = "ND01";

        if ($result && $result->num_rows > 0) {
            $lastCode = $result->fetch_assoc()['MaNguoiDung'];
            $number = (int)substr($lastCode, 2);
            $newCode = 'ND' . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
        }

        // Thêm người dùng
        $sql = "INSERT INTO nguoidung (MaNguoiDung, HoTen, Password, GioiTinh, NgaySinh, SDT, Email, DiaChi, ChucVu, MaPhongBan)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $newCode, $hoTen, $matKhau, $gioiTinh, $ngaySinh, $sdt, $email, $diaChi, $chucVu, $maPhongBan);

        if ($stmt->execute()) {
            echo '<script>
                alert("✅ Thêm người dùng thành công!");
                window.location.href = "quan-ly-nguoi-dung.php"; // Điều hướng về trang danh sách người dùng
            </script>';
            exit();
        } else {
            echo '<script>
                alert("❌ Lỗi: ' . addslashes($stmt->error) . '");
                window.history.back();
            </script>';
            exit();
        }
        

        $stmt->close();
    }

    $stmtPB->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Người Dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <link rel="stylesheet" href="../CSS/them-nguoi-dung.css">
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
    <section class="add-user">
        <h1>THÊM NGƯỜI DÙNG</h1>
       

        <form method="POST" action="">
            <label for="name">Họ tên</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ và tên người dùng" required>

            <label for="password">Mật khẩu</label>
            <input type="text" id="password" name="password" placeholder="Nhập mật khẩu"  required>

            <label for="gender">Giới tính</label>
            <select id="gender" name="gender" required>
                <option value="">Chọn giới tính</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
            </select>

            <label for="birthdate">Ngày sinh</label>
            <input type="date" id="birthdate" name="birthdate" placeholder="Nhập ngày sinh"  required>

            <label for="phone">SDT</label>
            <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Nhập email" required>

            <label for="address">Địa chỉ</label>
            <input type="text" id="address" name="address" placeholder="Nhập địa chỉ" required>

            <label for="role">Chức vụ</label>
            <select id="role" name="role" required>
                <option value="">Chọn chức vụ</option>
                <option value="Nhân viên">Nhân viên</option>
                <option value="Trưởng phòng">Trưởng phòng</option>
                <option value="Admin">Admin</option>
                <option value="Giám đốc">Giám đốc</option>
            </select>

            <label for="department">Phòng ban</label>
            <select id="department" name="department" required>
                <option value="">Chọn phòng ban</option>
                <?php foreach ($dsPhongBan as $pb): ?>
                    <option value="<?= htmlspecialchars($pb) ?>"><?= htmlspecialchars($pb) ?></option>
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
