<?php
session_start();
include 'db.php'; // file kết nối database

// Nhận dữ liệu từ form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Chống SQL Injection
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Tạo câu truy vấn
$sql = "SELECT * FROM nguoidung WHERE MaNguoiDung = '$username' AND Password = '$password'";
$result = mysqli_query($conn, $sql);

// Kiểm tra đăng nhập
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['MaNguoiDung'] = $row['MaNguoiDung'];
    $_SESSION['HoTen'] = $row['HoTen'];

    echo "success";
} else {
    echo "fail";
}
?>
