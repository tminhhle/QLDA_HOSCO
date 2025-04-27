<?php
include 'db.php'; // Kết nối CSDL

// Xử lý thêm công việc nếu form gửi dữ liệu POST lên
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenCV = $_POST['task_name'];
    $maDA = $_POST['project_id'];
    $maNguoiThucHien = $_POST['user_id'];
    $moTa = $_POST['description'];
    $ngayBDDuKien = $_POST['start_date_est'];
    $ngayKTDuKien = $_POST['end_date_est'];
    $ngayBDThucTe = $_POST['start_date_real'];
    $ngayKTThucTe = $_POST['end_date_real'];
    $trangThai = $_POST['status'];

    // Tạo mã công việc mới
    $query = "SELECT MaCV FROM congviec ORDER BY MaCV DESC LIMIT 1";
    $result = $conn->query($query);
    $newCode = "CV01";

    if ($result && $result->num_rows > 0) {
        $lastMaCV = $result->fetch_assoc()['MaCV'];
        $number = (int)substr($lastMaCV, 2);
        $newCode = 'CV' . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
    }

    // Thực hiện thêm công việc
    $sql = "INSERT INTO congviec (MaCV, TenCV, MaDA, MaNguoiThucHien, MoTa, NgayBDDuKien, NgayKTDuKien, NgayBDThucTe, NgayKTThucTe, TrangThai)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss", $newCode, $tenCV, $maDA, $maNguoiThucHien, $moTa, $ngayBDDuKien, $ngayKTDuKien, $ngayBDThucTe, $ngayKTThucTe, $trangThai);


    if ($stmt->execute()) {
        echo '
        <script>
            alert("✅ Thêm công việc thành công!");
            window.location.href = "qlcv.php";
        </script>
        ';
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    
}

// Lấy danh sách dự án và người dùng để hiện trong dropdown
$query_duan = "SELECT MaDA, TenDA FROM duan";
$result_duan = $conn->query($query_duan);

$query_nguoidung = " SELECT nd.MaNguoiDung, nd.HoTen, pb.TenPhongBan FROM nguoidung nd 
LEFT JOIN phongban pb ON nd.MaPhongBan = pb.MaPhongBan";

$result_nguoidung = $conn->query($query_nguoidung);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Công Việc</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <link rel="stylesheet" href="../CSS/tcv.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
                <i class="fa-regular fa-circle-user"></i> <span><strong>Minh</strong></span>
            </div>
        </div>
    </header>

    <div class="back-wrapper">
        <button class="back-btn" onclick="history.back()">← Quay lại</button>
    </div>

    <main>
        <section class="add-task">
            <h1>THÊM CÔNG VIỆC</h1>
            <form action="" method="POST">
                <label for="task-name">Tên công việc</label>
                <input type="text" id="task-name" name="task_name" placeholder="Nhập tên công việc" required>

                <label for="project-id">Dự án</label>
<select id="project-id" name="project_id" class="select2" required>
    <option value="">-- Tìm và chọn dự án --</option>
    <?php
    if ($result_duan->num_rows > 0) {
        while ($row = $result_duan->fetch_assoc()) {
            $optionText = $row['MaDA'] . ' - ' . $row['TenDA'];
            echo '<option value="' . $row['MaDA'] . '">' . $optionText . '</option>';
        }
    }
    ?>
</select>

<label for="user-id">Người thực hiện</label>
<select id="user-id" name="user_id" class="select2" required>
    <option value="">-- Tìm và chọn người --</option>
    <?php
    if ($result_nguoidung->num_rows > 0) {
        while ($row = $result_nguoidung->fetch_assoc()) {
            $optionText = $row['MaNguoiDung'] . ' - ' . $row['HoTen'] . ' - ' . $row['TenPhongBan'];
            echo '<option value="' . $row['MaNguoiDung'] . '">' . $optionText . '</option>';
        }
    }
    ?>
</select>




                <label for="description">Mô tả</label>
                <textarea id="description" name="description" placeholder="Nhập mô tả công việc" required></textarea>

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
                        <input type="datetime-local" id="start-date-real" name="start_date_real">
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

                <button type="submit">THÊM</button>
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

        $(document).ready(function() {
    // Khởi tạo select2 cho dự án
    $('#project-id').select2({
        placeholder: "-- Tìm và chọn dự án --",
        allowClear: true,
        width: '100%',
        language: {
        noResults: function() {
            return "Không có kết quả phù hợp";
        }
    },
        matcher: function(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            const term = params.term.toLowerCase();
            const text = data.text.toLowerCase();

            if (text.includes(term)) {
                return data;
            }

            return null;
        }
    });

    // Khởi tạo select2 cho người thực hiện
    $('#user-id').select2({
        placeholder: "-- Tìm và chọn người --",
        allowClear: true,
        width: '100%',
        language: {
        noResults: function() {
            return "Không có kết quả phù hợp";
        }
    },
        matcher: function(params, data) {
    if ($.trim(params.term) === '') {
        return data;
    }

    const term = params.term.toLowerCase();
    const text = data.text.toLowerCase();

    if (text.includes(term)) {
        return data;
    }

    return null;
}

    });
});


      </script>



</body>
</html>
<?php
$conn->close();
?>
