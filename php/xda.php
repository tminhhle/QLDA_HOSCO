<?php
include 'db.php';

if (isset($_GET['MaDA'])) {
  $maDA = $_GET['MaDA'];

  // Lấy thông tin dự án + người tạo
  $sql_project = "
    SELECT da.*, nd.HoTen AS TenNguoiTao
    FROM qlda.duan da
    LEFT JOIN qlda.nguoidung nd ON da.MaNguoiTao = nd.MaNguoiDung
    WHERE da.MaDA = '$maDA'
  ";
  $result_project = $conn->query($sql_project);
  $duan = $result_project->fetch_assoc();

 // Lấy danh sách thành viên tham gia (JOIN thêm bảng phongban)
$sql_members = "
SELECT nd.MaNguoiDung, nd.HoTen, pb.TenPhongBan, ndd.VaiTro
FROM qlda.nguoidungduan ndd
JOIN qlda.nguoidung nd ON ndd.MaNguoiThamGia = nd.MaNguoiDung
LEFT JOIN qlda.phongban pb ON nd.MaPhongBan = pb.MaPhongBan
WHERE ndd.MaDA = '$maDA'
";

$members = $conn->query($sql_members);

  // Lấy danh sách công việc
  $sql_tasks = "
    SELECT cv.*, nd.HoTen AS TenNguoiThucHien
    FROM qlda.congviec cv
    LEFT JOIN qlda.nguoidung nd ON cv.MaNguoiThucHien = nd.MaNguoiDung
    WHERE cv.MaDA = '$maDA'
  ";
  $tasks = $conn->query($sql_tasks);
} else {
  echo "Không có mã dự án.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xem dự án</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
  <link rel="stylesheet" href="../CSS/xda.css">
</head>
<body>
<header>
  <div class="navbar">
    <a href="#">Trang chủ</a>
    <a href="#" class="active">Quản lý dự án</a>
    <a href="#">Quản lý công việc</a>
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
  <section class="view-project">
    <h1>XEM DỰ ÁN</h1> 
    <div class="project-details">
      <div class="detail"><label>Mã dự án:</label><span><?= $duan['MaDA'] ?></span></div>
      <div class="detail"><label>Tên dự án:</label><span><?= $duan['TenDA'] ?></span></div>
      <div class="detail"><label>Mô tả:</label><span><?= $duan['MoTa'] ?></span></div>
      <div class="detail"><label>Ngày bắt đầu dự kiến:</label><span><?= $duan['NgayBDDuKien'] ?></span></div>
      <div class="detail"><label>Ngày kết thúc dự kiến:</label><span><?= $duan['NgayKTDuKien'] ?></span></div>
      <div class="detail"><label>Ngày bắt đầu thực tế:</label>
  <span><?= (!empty($duan['NgayBDThucTe']) && $duan['NgayBDThucTe'] !== '0000-00-00 00:00:00') ? $duan['NgayBDThucTe'] : '' ?></span>
</div>

<div class="detail"><label>Ngày kết thúc thực tế:</label>
  <span><?= (!empty($duan['NgayKTThucTe']) && $duan['NgayKTThucTe'] !== '0000-00-00 00:00:00') ? $duan['NgayKTThucTe'] : '' ?></span>
</div>


      <div class="detail"><label>Trạng thái:</label><span><?= $duan['TrangThai'] ?></span></div>
      <div class="detail"><label>Người tạo dự án:</label><span><?= $duan['TenNguoiTao'] ?></span></div>
    </div>

    <h2>Thông tin người tham gia:</h2>
    <table class="participant-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã người dùng</th>
          <th>Họ tên</th>
          <th>Phòng ban</th>
          <th>Vai trò</th>
          <th>Xem</th>
        </tr>
      </thead>
      <tbody>
        <?php $stt = 1; while($row = $members->fetch_assoc()) { ?>
        <tr>
          <td><?= $stt++ ?></td>
          <td><?= $row['MaNguoiDung'] ?></td>
          <td><?= $row['HoTen'] ?></td>
          <td><?= $row['TenPhongBan'] ?></td>
          <td><?= $row['VaiTro'] ?></td>
          <td><a href="#" class="view-icon"><i class="fa-regular fa-eye"></i></a></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <h2>Công việc:</h2>
    <table class="work-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã công việc</th>
          <th>Tên công việc</th>
          <th>Người thực hiện</th>
          <th>Trạng thái</th>
          <th>Xem</th>
        </tr>
      </thead>
      <tbody>
        <?php $stt = 1; while($row = $tasks->fetch_assoc()) { ?>
        <tr>
          <td><?= $stt++ ?></td>
          <td><?= $row['MaCV'] ?></td>
          <td><?= $row['TenCV'] ?></td>
          <td><?= $row['TenNguoiThucHien'] ?></td>
          <td><?= $row['TrangThai'] ?></td>
          <td><a href="#" class="view-icon"><i class="fa-regular fa-eye"></i></a></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>
</main>

</body>
</html>
