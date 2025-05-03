<?php
include 'db.php';

if (isset($_GET['MaCV'])) {
    $maCV = $_GET['MaCV'];

    // Lấy mã dự án trước khi xóa
    $getDA = $conn->prepare("SELECT MaDA, TrangThai FROM congviec WHERE MaCV = ?");
    $getDA->bind_param("s", $maCV);
    $getDA->execute();
    $resultDA = $getDA->get_result();

    if ($resultDA && $resultDA->num_rows > 0) {
        $row = $resultDA->fetch_assoc();
        $maDA = $row['MaDA'];
        $trangThai = $row['TrangThai'];

        // Chỉ cho phép xóa nếu là "Chưa bắt đầu" hoặc "Hoàn thành"
        if ($trangThai === 'Chưa bắt đầu' || $trangThai === 'Hoàn thành') {
            // Thực hiện xóa công việc
            $stmtDel = $conn->prepare("DELETE FROM congviec WHERE MaCV = ?");
            $stmtDel->bind_param("s", $maCV);

            if ($stmtDel->execute()) {
                // Cập nhật trạng thái dự án sau khi xóa
                $stmtCheck = $conn->prepare("
                    SELECT 
                        COUNT(*) AS total,
                        SUM(CASE WHEN TrangThai = 'Hoàn thành' THEN 1 ELSE 0 END) AS done,
                        SUM(CASE WHEN TrangThai = 'Chưa bắt đầu' THEN 1 ELSE 0 END) AS not_started
                    FROM congviec
                    WHERE MaDA = ?
                ");
                $stmtCheck->bind_param("s", $maDA);
                $stmtCheck->execute();
                $resCheck = $stmtCheck->get_result();
                $row = $resCheck->fetch_assoc();

                $new_status = null;
                if ($row['total'] == 0) {
                    $new_status = 'Chưa bắt đầu';
                } elseif ($row['done'] == $row['total']) {
                    $new_status = 'Hoàn thành';
                } elseif ($row['not_started'] == $row['total']) {
                    $new_status = 'Chưa bắt đầu';
                }

                if ($new_status) {
                    $stmtUpdate = $conn->prepare("UPDATE duan SET TrangThai = ? WHERE MaDA = ?");
                    $stmtUpdate->bind_param("ss", $new_status, $maDA);
                    $stmtUpdate->execute();
                }

                echo "success";
            } else {
                echo "fail";
            }
        } else {
            echo "not_allowed";
        }
    } else {
        echo "not_found";
    }
} else {
    echo "invalid";
}

