<?php
include 'db.php';

if (isset($_GET['MaDA'])) {
    $maDA = $_GET['MaDA'];

    // Truy vấn trạng thái dự án trước
    $checkSql = "SELECT TrangThai FROM qlda.duan WHERE MaDA = '$maDA'";
    $result = mysqli_query($conn, $checkSql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $trangThai = $row['TrangThai'];

        // Chỉ cho phép xóa nếu là "Chưa bắt đầu" hoặc "Hoàn thành"
        if ($trangThai === 'Chưa bắt đầu' || $trangThai === 'Hoàn thành') {
            // Xóa dữ liệu liên quan trước
            $sql1 = "DELETE FROM qlda.congviec WHERE MaDA = '$maDA'";
            $sql2 = "DELETE FROM qlda.nguoidungduan WHERE MaDA = '$maDA'";
            $sql3 = "DELETE FROM qlda.duan WHERE MaDA = '$maDA'";

            if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3)) {
                echo "success";
            } else {
                echo "fail"; // Lỗi trong quá trình xóa
            }
        } else {
            echo "not_allowed"; // Dự án đang tiến hành → không được xóa
        }
    } else {
        echo "not_found"; // Không tìm thấy dự án
    }
} else {
    echo "invalid"; // Không truyền mã dự án
}
?>
