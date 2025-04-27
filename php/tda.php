<?php
include 'db.php'; // Kết nối CSDL

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenDA = $_POST['project_name'];
    $moTa = $_POST['description'];
    $ngayBDDuKien = $_POST['start_date_est'];
    $ngayKTDuKien = $_POST['end_date_est'];
    $ngayBDThucTe = $_POST['start_date_real'];
    $ngayKTThucTe = $_POST['end_date_real'];
    $trangThai = $_POST['status'];

 



    $query = "SELECT MaDA FROM duan ORDER BY MaDA DESC LIMIT 1";
    $result = $conn->query($query);
    $newCode = "DA01";

    if ($result && $result->num_rows > 0) {
        $lastMaDA = $result->fetch_assoc()['MaDA'];
        $number = (int)substr($lastMaDA, 2);
        $newCode = 'DA' . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
    }

    $sql = "INSERT INTO duan (MaDA, TenDA, MoTa, NgayBDDuKien, NgayKTDuKien, NgayBDThucTe, NgayKTThucTe, TrangThai)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $newCode, $tenDA, $moTa, $ngayBDDuKien, $ngayKTDuKien, $ngayBDThucTe, $ngayKTThucTe, $trangThai);

    if ($stmt->execute()) {
        echo '
        <script>
            alert("✅ Thêm dự án thành công!");
            window.location.href = "../php/qlda.php";
        </script>
        ';
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


