<?php
include 'db.php';

if (!isset($_GET['MaDA'])) {
  echo "Mã dự án không hợp lệ!";
  exit;
}

$maDA = $_GET['MaDA'];
$keyword = $_GET['keyword'] ?? '';

// Lấy dữ liệu dự án từ DB
$sql = "SELECT * FROM qlda.duan WHERE MaDA = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maDA);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "Không tìm thấy dự án!";
  exit;
}

$duan = $result->fetch_assoc();

// Xử lý cập nhật nếu có submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $tenDA = $_POST['TenDA'];
    $moTa = $_POST['MoTa'];
    $ngayBD_KH = $_POST['NgayBDDuKien'];
    $ngayKT_KH = $_POST['NgayKTDuKien'];
    $ngayBD_TT = $_POST['NgayBDThucTe'];
    $ngayKT_TT = $_POST['NgayKTThucTe'];
    $trangThai = $_POST['TrangThai'];

    $keyword = $_POST['keyword'] ?? '';

    $sql_update = "UPDATE qlda.duan 
                   SET TenDA=?, MoTa=?, NgayBDDuKien=?, NgayKTDuKien=?, NgayBDThucTe=?, NgayKTThucTe=?, TrangThai=? 
                   WHERE MaDA=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssss", $tenDA, $moTa, $ngayBD_KH, $ngayKT_KH, $ngayBD_TT, $ngayKT_TT, $trangThai, $maDA);

    if ($stmt_update->execute()) {
      echo "<script>
          alert('✅ Sửa dự án thành công!');
            
          window.location.href = '../php/qlda.php?search=" . urlencode($keyword) . "';
      </script>";
      exit(); 
  
   
        
    } else {
        echo "Cập nhật thất bại!";
    }
}
?>

<!-- Giao diện form giống bạn gửi -->
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa Dự Án</title>
  <link rel="stylesheet" href="../CSS/sda.css">
  <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
</head>
<body>
<header>
        <div class="navbar">
            <a href="#">Trang chủ</a>
            <a class="active" href="#">Quản lý dự án</a>
            <a href="#">Quản lý công việc</a>
            <a href="#">Quản lý người dùng</a>
            <a href="#">Thống kê</a>
            <div class="user-info">
                <i class="fa-regular fa-circle-user"></i> <span><strong>Minh</strong></span>
            </div>
        </div>
    </header>
  <div class="back-wrapper">
    <button class="back-btn" onclick="history.back()">← Quay lại</button>
  </div>

  <main>
    <section class="edit-project">
      <h1>SỬA DỰ ÁN</h1>
      <form method="post">
      <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
        <label for="project-name">Tên dự án</label>
        <input type="text" id="project-name" name="TenDA" value="<?= htmlspecialchars($duan['TenDA']) ?>" required>

        <label for="description">Mô tả</label>
        <textarea id="description" name="MoTa" required><?= htmlspecialchars($duan['MoTa']) ?></textarea>

        <div class="date-grid">
          <div>
            <label for="start-date-est">Ngày bắt đầu dự kiến</label>
            <input type="datetime-local" id="start-date-est" name="NgayBDDuKien" value="<?= date('Y-m-d\TH:i', strtotime($duan['NgayBDDuKien'])) ?>" required>
          </div>
          <div>
            <label for="end-date-est">Ngày kết thúc dự kiến</label>
            <input type="datetime-local" id="end-date-est" name="NgayKTDuKien" value="<?= date('Y-m-d\TH:i', strtotime($duan['NgayKTDuKien'])) ?>" required>
          </div>
        </div>

        <div class="date-grid">
          <div>
            <label for="start-date-real">Ngày bắt đầu thực tế</label>
            <input type="datetime-local" id="start-date-real" name="NgayBDThucTe" value="<?= date('Y-m-d\TH:i', strtotime($duan['NgayBDThucTe'])) ?>">
          </div>
          <div>
            <label for="end-date-real">Ngày kết thúc thực tế</label>
            <input type="datetime-local" id="end-date-real" name="NgayKTThucTe" value="<?= date('Y-m-d\TH:i', strtotime($duan['NgayKTThucTe'])) ?>" >
          </div>
        </div>
        <label for="status">Trạng thái</label>
        <select id="status" name="TrangThai" required>
  <option value="Chưa bắt đầu" <?= $duan['TrangThai'] == 'Chưa bắt đầu' ? 'selected' : '' ?>>Chưa bắt đầu</option>
  <option value="Đang tiến hành" <?= $duan['TrangThai'] == 'Đang tiến hành' ? 'selected' : '' ?>>Đang tiến hành</option>
  <option value="Hoàn thành" <?= $duan['TrangThai'] == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
</select>


        <button type="submit">CẬP NHẬT</button>
      </form>
    </section>
  </main>
</body>
</html>
