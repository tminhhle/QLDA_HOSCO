<?php
include 'db.php';
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
  $sql = "SELECT nguoidung.*, phongban.TenPhongBan 
  FROM qlda.nguoidung 
  JOIN phongban ON nguoidung.MaPhongBan = phongban.MaPhongBan 
  WHERE MaNguoiDung LIKE ? 
     OR HoTen LIKE ? 
     OR ChucVu LIKE ? 
     OR phongban.TenPhongBan LIKE ?";

            
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT nguoidung.*, phongban.TenPhongBan 
  FROM qlda.nguoidung 
  JOIN phongban ON nguoidung.MaPhongBan = phongban.MaPhongBan";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý người dùng</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
  <link rel="stylesheet" href="../CSS/quan-ly-nguoi-dung.css">
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

  <main>
    <section class="user-list">
      <h1>DANH SÁCH NGƯỜI DÙNG</h1>
      <form method="GET" class="actions">
        <a href="them-nguoi-dung.php" class="btn-add"><strong>+ THÊM</strong></a>
        <div style="display: flex; align-items: center; gap: 10px;">
          <input type="text" name="search" placeholder="Tìm theo mã, tên, chức vụ, phòng ban..." value="<?= htmlspecialchars($search) ?>">
          <button type="submit" class="btn-search">Tìm</button>
          <?php if ($search !== ''): ?>
            <a href="quan-ly-nguoi-dung.php" class="btn-back">← Quay lại</a>
          <?php endif; ?>
        </div>
      </form>

      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>MÃ NGƯỜI DÙNG</th>
            <th>HỌ TÊN NGƯỜI DÙNG</th>
            <th>CHỨC VỤ</th>
            <th>PHÒNG BAN</th>
            <th>XEM</th>
            <th>SỬA</th>
            <th>XÓA</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stt = 1;
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?= $stt++ ?></td>
                <td><?= $row['MaNguoiDung'] ?></td>
                <td><?= $row['HoTen'] ?></td>
                <td><?= $row['ChucVu'] ?></td>
                <td><?= $row['TenPhongBan'] ?></td>
                <td><a href="xem-nguoi-dung.php?MaNguoiDung=<?= $row['MaNguoiDung'] ?>"><i class="fa-regular fa-eye"></i></a></td>
                <td><a href="sua-nguoi-dung.php?MaNguoiDung=<?= $row['MaNguoiDung'] ?>&search=<?= urlencode($search) ?>"><i class="fa-regular fa-pen-to-square"></i></a></td>
                <td><i class="fa-regular delete-btn fa-trash-can" onclick="showPopup('<?= $row['MaNguoiDung'] ?>')"></i></td>
              </tr>
            <?php }
          } else {
            echo '<tr><td colspan="8" style="text-align:center;">Không tìm thấy người dùng phù hợp.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>

  <div class="popup-overlay" id="deletePopup" style="display:none;">
    <div class="popup-box">
      <h2>Xác nhận xóa</h2>
      <p>Bạn có chắc chắn muốn xóa <strong id="projectName"></strong>?</p>
      <button class="btn-confirm" onclick="confirmDelete()">Xóa</button>
      <button class="btn-cancel" onclick="closePopup()">Hủy</button>
    </div>
  </div>

  <script>
    let selectedProject = '';

    function showPopup(projectName) {
      selectedProject = projectName;
      document.getElementById("projectName").innerText = projectName;
      document.getElementById("deletePopup").style.display = "flex";
    }

    function closePopup() {
      document.getElementById("deletePopup").style.display = "none";
    }

    function confirmDelete() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "xoa-nguoi-dung.php?MaNguoiDung=" + selectedProject, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();
      if (response === "success") {
        alert("Đã xóa thành công người dùng " + selectedProject);
        location.reload();
      } else if (response === "in_use") {
        alert("Không thể xóa người dùng vì đang thực hiện công việc.");
      } else {
        alert("Xóa thất bại. Vui lòng thử lại.");
      }
    }
  };
  xhr.send();
  closePopup();
}
function showLogout() {
    document.getElementById("logoutMenu").style.display = "block";
}

function hideLogout() {
    document.getElementById("logoutMenu").style.display = "none";
}

  </script>
</body>
</html>
