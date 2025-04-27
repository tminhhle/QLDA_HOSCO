<?php
include 'db.php';

if (isset($_GET['MaCV'])) {
    $maCV = $_GET['MaCV'];

    // Truy vấn công việc + người thực hiện + người tạo + dự án
    $sql_task = "
        SELECT cv.*, 
               nd1.HoTen AS TenNguoiThucHien, 
               nd2.HoTen AS TenNguoiTao,
               da.MaDA, da.TenDA,
               pb1.TenPhongBan AS PhongBanNguoiThucHien,
               pb2.TenPhongBan AS PhongBanNguoiTao
        FROM qlda.congviec cv
        LEFT JOIN qlda.nguoidung nd1 ON cv.MaNguoiThucHien = nd1.MaNguoiDung
        LEFT JOIN qlda.nguoidung nd2 ON cv.MaNguoiTao = nd2.MaNguoiDung
        LEFT JOIN qlda.duan da ON cv.MaDA = da.MaDA
        LEFT JOIN qlda.phongban pb1 ON nd1.MaPhongBan = pb1.MaPhongBan
        LEFT JOIN qlda.phongban pb2 ON nd2.MaPhongBan = pb2.MaPhongBan
        WHERE cv.MaCV = '$maCV'
    ";

    $result = $conn->query($sql_task);
    $congviec = $result->fetch_assoc();

    if (!$congviec) {
        echo "Không tìm thấy công việc.";
        exit;
    }

    // Lấy thông tin người tạo dự án
    $maDA = $congviec['MaDA'];
    $sql_project = "
        SELECT da.*, 
               nd.HoTen AS TenNguoiTao, 
               nd.MaNguoiDung AS MaNguoiTao, 
               pb.TenPhongBan AS PhongBanNguoiTao
        FROM qlda.duan da
        LEFT JOIN qlda.nguoidung nd ON da.MaNguoiTao = nd.MaNguoiDung
        LEFT JOIN qlda.phongban pb ON nd.MaPhongBan = pb.MaPhongBan
        WHERE da.MaDA = '$maDA'
    ";
    $result_project = $conn->query($sql_project);
    $duan = $result_project->fetch_assoc();

} else {
    echo "Thiếu mã công việc.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xem Công Việc</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/xcv.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="#">Trang chủ</a>
            <a href="#">Quản lý dự án</a>
            <a class="active" href="#">Quản lý công việc</a>
            <a href="#">Quản lý người dùng</a>
            <a href="#">Thống kê</a>
            <div class="user-info">
                <i class="fa-regular fa-circle-user"></i><span><strong>Minh</strong></span>
            </div>
        </div>
    </header>

    <div class="back-wrapper">
        <button class="back-btn" onclick="history.back()">← Quay lại</button>
    </div>

    <main>
        <section class="view-task">
            <h1>XEM CÔNG VIỆC</h1>
            <div class="task-details">
                <div class="detail"><label>Mã công việc:</label> <span><?= $congviec['MaCV'] ?></span></div>
                <div class="detail"><label>Tên công việc:</label> <span><?= $congviec['TenCV'] ?></span></div>
                <div class="detail"><label>Dự án:</label> <span><?= $congviec['MaDA'] . ' - ' . $congviec['TenDA'] ?></span></div>
                <div class="detail"><label>Mô tả:</label> <span><?= $congviec['MoTa'] ?></span></div>
                <div class="detail"><label>Ngày bắt đầu dự kiến:</label> <span><?= $congviec['NgayBDDuKien'] ?></span></div>
                <div class="detail"><label>Ngày kết thúc dự kiến:</label> <span><?= $congviec['NgayKTDuKien'] ?></span></div>
                <div class="detail"><label>Ngày bắt đầu thực tế:</label> 
                    <span><?= ($congviec['NgayBDThucTe'] != '0000-00-00 00:00:00') ? $congviec['NgayBDThucTe'] : '' ?></span>
                </div>
                <div class="detail"><label>Ngày kết thúc thực tế:</label> 
                    <span><?= ($congviec['NgayKTThucTe'] != '0000-00-00 00:00:00') ? $congviec['NgayKTThucTe'] : '' ?></span>
                </div>
                <div class="detail"><label>Trạng thái:</label> <span><?= $congviec['TrangThai'] ?></span></div>
                <div class="detail"><label>Người tạo dự án:</label>
                    <span><?= $duan['MaNguoiTao'] . ' - ' . $duan['TenNguoiTao'] . ' - ' . $duan['PhongBanNguoiTao'] ?></span>
                </div>
            </div>

            <h2>Người thực hiện công việc:</h2>
            <table class="participant-table">
                <thead>
                    <tr>
                        <th>Mã người dùng</th>
                        <th>Họ tên</th>
                        <th>Phòng ban</th>
                        <th>Xem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($congviec['MaNguoiThucHien']) { ?>
                    <tr>
                        <td><?= $congviec['MaNguoiThucHien'] ?></td>
                        <td><?= $congviec['TenNguoiThucHien'] ?></td>
                        <td><?= $congviec['PhongBanNguoiThucHien'] ?></td>
                        <td><i class="fa-regular fa-eye"></i></td>
                    </tr>
                    <?php } else { ?>
                    <tr><td colspan="4">Không có người thực hiện</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
