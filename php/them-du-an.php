<?php
include 'db.php'; // Kết nối CSDL
session_start();
$tenNguoiDung = isset($_SESSION['HoTen']) ? $_SESSION['HoTen'] : 'Khách';
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
            window.location.href = "../php/quan-ly-du-an.php";
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


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Dự Án</title>
    <link rel="stylesheet"  href= "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <link rel="stylesheet" href="../CSS/them-du-an.css">
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
   
    <div class="back-wrapper">
        <!--<button class="back-btn" onclick="history.back()">← Quay lại</button>-->
        <button class="back-btn" onclick="history.back()">← Quay lại</button>
    </div>


    <main>
        
        <section class="add-project">
            <h1>THÊM DỰ ÁN</h1>
            
            <form action="" method="POST">
                <label for="project-name">Tên dự án</label>
                <input type="text" id="project-name" name="project_name" placeholder="Nhập tên dự án" required>
            
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" placeholder="Nhập mô tả dự án" required></textarea>
            
                <div class="date-grid">
                    <div>
                        <label for="start-date-est">Ngày bắt đầu dự kiến</label>
                        <input type="datetime-local" id="start-date-est" name="start_date_est" required>
                    </div>
                    <div>
                        <label for="end-date-est">Ngày kết thúc dự kiến</label>
                        <input type="datetime-local" id="end-date-est" name="end_date_est" required>
                    </div>
                </div>
            
                <div class="date-grid">
                    <div>
                        <label for="start-date-real">Ngày bắt đầu thực tế</label>
                        <input type="datetime-local" id="start-date-real" name="start_date_real" >
                    </div>
                    <div>
                        <label for="end-date-real">Ngày kết thúc thực tế</label>
                        <input type="datetime-local" id="end-date-real" name="end_date_real" >
                    </div>
                </div>
            
                <label for="status">Trạng thái</label>
                <select id="status" name="status" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="Chưa bắt đầu">Chưa bắt đầu</option>
                    <option value="Đang tiến hành">Đang tiến hành</option>
                    <option value="Hoàn thành">Hoàn thành</option>
                  </select>
                  
            
                <button type="submit">LƯU</button>
            </form>
        </section>
    </main>

    <script>
        function validateDateInputs(estStartId, estEndId, realStartId, realEndId) {
          const estStart = document.getElementById(estStartId);
          const estEnd = document.getElementById(estEndId);
          const realStart = document.getElementById(realStartId);
          const realEnd = document.getElementById(realEndId);
      
          // Kiểm tra dự kiến
          estEnd.addEventListener('change', () => {
            if (estStart.value && estEnd.value && estEnd.value < estStart.value) {
              alert("❌ Ngày kết thúc dự kiến phải sau ngày bắt đầu dự kiến!");
              estEnd.value = '';
            }
          });
      
          estStart.addEventListener('change', () => {
            if (estStart.value && estEnd.value && estEnd.value < estStart.value) {
              alert("❌ Ngày kết thúc dự kiến phải sau ngày bắt đầu dự kiến!");
              estEnd.value = '';
            }
          });
      
          // Kiểm tra thực tế
          realEnd.addEventListener('change', () => {
            if (realStart.value && realEnd.value && realEnd.value < realStart.value) {
              alert("❌ Ngày kết thúc thực tế phải sau ngày bắt đầu thực tế!");
              realEnd.value = '';
            }
          });
      
          realStart.addEventListener('change', () => {
            if (realStart.value && realEnd.value && realEnd.value < realStart.value) {
              alert("❌ Ngày kết thúc thực tế phải sau ngày bắt đầu thực tế!");
              realEnd.value = '';
            }
          });
        }
      
        // Gọi hàm khi DOM đã sẵn sàng
        window.addEventListener('DOMContentLoaded', () => {
          validateDateInputs('start-date-est', 'end-date-est', 'start-date-real', 'end-date-real');
        });

        function showLogout() {
    document.getElementById("logoutMenu").style.display = "block";
}

function hideLogout() {
    document.getElementById("logoutMenu").style.display = "none";
}
      </script>
      
</body>
</html>



