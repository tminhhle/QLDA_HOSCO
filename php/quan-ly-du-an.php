<?php
include 'db.php';
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $sql = "SELECT * FROM qlda.duan 
            WHERE TenDA LIKE ? OR MaDA LIKE ? OR MoTa LIKE ? OR TrangThai LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM qlda.duan";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý dự án</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
  <link rel="stylesheet" href="../CSS/quan-ly-du-an.css">
</head>
<body>
   <header>
        <nav class="navbar">
            <a href="trang-chu.php">Trang chủ</a>
            <a class="active" href="../php/quan-ly-du-an.php">Quản lý dự án</a>
            <a  href="quan-ly-cong-viec.php">Quản lý công việc</a>
            <a href="quan-ly-nguoi-dung.php">Quản lý người dùng</a>
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
    <section class="project-list">
      <h1>DANH SÁCH DỰ ÁN</h1>
      <form method="GET" class="actions">
        
      <a href="them-du-an.php" class="btn-add"><strong>+ THÊM</strong></a>


  <div style="display: flex; align-items: center; gap: 10px;">
    <input type="text" name="search" placeholder="Tìm kiếm theo mã, tên, mô tả, trạng thái..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn-search">Tìm</button>
    <?php if ($search !== ''): ?>
      <a href="quan-ly-du-an.php" class="btn-back">← Quay lại</a>
    <?php endif; ?>
  </div>
</form>

      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>MÃ DỰ ÁN</th>
            <th>TÊN DỰ ÁN</th>
            <th>TRẠNG THÁI</th>
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
                <td><?= $row['MaDA'] ?></td>
                <td><?= $row['TenDA'] ?></td>
                <td><?= $row['TrangThai'] ?></td>   
                <td><a href="xem-du-an.php?MaDA=<?= $row['MaDA'] ?>"><i class="fa-regular fa-eye"></i></a></td>
                <td><a href="sua-du-an.php?MaDA=<?= $row['MaDA'] ?>&search=<?= urlencode($search) ?>"><i class="fa-regular fa-pen-to-square"></i></a></td>
                <td><i class="fa-regular delete-btn fa-trash-can" onclick="showPopup('<?= $row['MaDA'] ?>')"></i></td>
              </tr>
            <?php } 
          } else {
            echo '<tr><td colspan="7" style="text-align:center;">Không tìm thấy dự án phù hợp.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Popup xác nhận xóa -->
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
      xhr.open("GET", "xoa-du-an.php?MaDA=" + selectedProject, true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          const response = xhr.responseText.trim();
          if (response === "success") {
            alert("Đã xóa thành công dự án " + selectedProject);
            location.reload();
          } else if (response === "not_allowed") {
            alert("Không thể xóa dự án đang tiến hành!");
          } else if (response === "not_found") {
            alert("Dự án không tồn tại!");
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
