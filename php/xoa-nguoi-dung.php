<?php
include 'db.php';

if (isset($_GET['MaNguoiDung'])) {
    $maND = $_GET['MaNguoiDung'];

    // Kiểm tra người dùng có tồn tại không
    $stmtCheck = $conn->prepare("SELECT * FROM nguoidung WHERE MaNguoiDung = ?");
    $stmtCheck->bind_param("s", $maND);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck && $resultCheck->num_rows > 0) {
        // 1. Kiểm tra công việc đang thực hiện
        $stmtCV = $conn->prepare("SELECT * FROM congviec WHERE MaNguoiThucHien = ? AND TrangThai = 'Đang thực hiện'");
        $stmtCV->bind_param("s", $maND);
        $stmtCV->execute();
        $resultCV = $stmtCV->get_result();

        if ($resultCV->num_rows > 0) {
            echo "in_use"; // Đang thực hiện công việc, không được xóa
            exit;
        }

        // 2. Kiểm tra liên quan khác (không cho phép xóa nếu có liên kết)
        $tablesToCheck = [
            ["duan", "MaNguoiTao"],
            ["nguoidungduan", "MaNguoiThamGia"],
            ["thongke", "MaNguoiTao"]
        ];

        foreach ($tablesToCheck as [$table, $column]) {
            $stmt = $conn->prepare("SELECT 1 FROM $table WHERE $column = ? LIMIT 1");
            $stmt->bind_param("s", $maND);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                echo "in_use"; // Có liên kết, không được xóa
                exit;
            }
        }

        // 3. Xóa nếu không bị ràng buộc
        $stmtDel = $conn->prepare("DELETE FROM nguoidung WHERE MaNguoiDung = ?");
        $stmtDel->bind_param("s", $maND);
        if ($stmtDel->execute()) {
            echo "success";
        } else {
            echo "fail";
        }
    } else {
        echo "not_found";
    }
} else {
    echo "invalid";
}

