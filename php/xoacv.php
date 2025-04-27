<?php
include 'db.php';

if (isset($_GET['MaCV'])) {
    $maCV = $_GET['MaCV'];

    // Kiểm tra trạng thái công việc
    $checkSql = "SELECT TrangThai FROM qlda.congviec WHERE MaCV = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $maCV);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $trangThai = $row['TrangThai'];

        // Chỉ cho phép xóa nếu là "Chưa bắt đầu" hoặc "Hoàn thành"
        if ($trangThai === 'Chưa bắt đầu' || $trangThai === 'Hoàn thành') {
            // Thực hiện xóa công việc
            $deleteSql = "DELETE FROM qlda.congviec WHERE MaCV = ?";
            $stmtDel = $conn->prepare($deleteSql);
            $stmtDel->bind_param("s", $maCV);

            if ($stmtDel->execute()) {
                echo "success";
            } else {
                echo "fail"; // Lỗi trong quá trình xóa
            }
        } else {
            echo "not_allowed"; // Công việc đang tiến hành → không được xóa
        }
    } else {
        echo "not_found"; // Không tìm thấy công việc
    }
} else {
    echo "invalid"; // Không truyền mã công việc
}
?>
