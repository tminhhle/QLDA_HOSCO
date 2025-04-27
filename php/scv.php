<?php
include 'db.php';

$maCV = $_GET['MaCV'] ?? '';
if (!$maCV) {
    echo "Không tìm thấy công việc.";
    exit();
}

$keyword = $_GET['keyword'] ?? '';

// Xử lý cập nhật nếu form được gửi
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

    $keyword = $_POST['keyword'] ?? '';


    $sql_update = "UPDATE congviec SET TenCV=?, MaDA=?, MaNguoiThucHien=?, MoTa=?, NgayBDDuKien=?, NgayKTDuKien=?, NgayBDThucTe=?, NgayKTThucTe=?, TrangThai=? WHERE MaCV=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssssssss", $tenCV, $maDA, $maNguoiThucHien, $moTa, $ngayBDDuKien, $ngayKTDuKien, $ngayBDThucTe, $ngayKTThucTe, $trangThai, $maCV);

    if ($stmt->execute()) {
        echo "<script>alert('Sửa công việc thành công!'); window.location.href = '../php/qlcv.php?search=" . urlencode($keyword) . "';</script>";

        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}

// Lấy dữ liệu công việc để hiển thị
$sql_cv = "SELECT * FROM congviec WHERE MaCV = ?";
$stmt = $conn->prepare($sql_cv);
$stmt->bind_param("s", $maCV);
$stmt->execute();
$result_cv = $stmt->get_result();
$cv = $result_cv->fetch_assoc();
$stmt->close();

// Lấy danh sách dự án và người dùng
$query_duan = "SELECT MaDA, TenDA FROM duan";
$result_duan = $conn->query($query_duan);

$query_nguoidung = "SELECT nd.MaNguoiDung, nd.HoTen, pb.TenPhongBan FROM nguoidung nd 
LEFT JOIN phongban pb ON nd.MaPhongBan = pb.MaPhongBan";
$result_nguoidung = $conn->query($query_nguoidung);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Công Việc</title>
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
            <h1>SỬA CÔNG VIỆC</h1>
            <form action="" method="POST">
            <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                <label for="task-name">Tên công việc</label>
                <input type="text" id="task-name" name="task_name" value="<?= $cv['TenCV'] ?>" required>

                <label for="project-id">Dự án</label>
                <select id="project-id" name="project_id" class="select2" required>
                    <option value="">-- Tìm và chọn dự án --</option>
                    <?php while ($row = $result_duan->fetch_assoc()): ?>
                        <option value="<?= $row['MaDA'] ?>" <?= $cv['MaDA'] == $row['MaDA'] ? 'selected' : '' ?>>
                            <?= $row['MaDA'] . ' - ' . $row['TenDA'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="user-id">Người thực hiện</label>
                <select id="user-id" name="user_id" class="select2" required>
                    <option value="">-- Tìm và chọn người --</option>
                    <?php while ($row = $result_nguoidung->fetch_assoc()): ?>
                        <option value="<?= $row['MaNguoiDung'] ?>" <?= $cv['MaNguoiThucHien'] == $row['MaNguoiDung'] ? 'selected' : '' ?>>
                            <?= $row['MaNguoiDung'] . ' - ' . $row['HoTen'] . ' - ' . $row['TenPhongBan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="description">Mô tả</label>
                <textarea id="description" name="description" required><?= $cv['MoTa'] ?></textarea>

                <div class="date-grid">
                    <div>
                        <label for="start-date-est">Ngày bắt đầu dự kiến</label>
                        <input type="datetime-local" id="start-date-est" name="start_date_est" value="<?= $cv['NgayBDDuKien'] ?>" required>
                    </div>
                    <div>
                        <label for="end-date-est">Ngày kết thúc dự kiến</label>
                        <input type="datetime-local" id="end-date-est" name="end_date_est" value="<?= $cv['NgayKTDuKien'] ?>" required>
                    </div>
                </div>

                <div class="date-grid">
                    <div>
                        <label for="start-date-real">Ngày bắt đầu thực tế</label>
                        <input type="datetime-local" id="start-date-real" name="start_date_real" value="<?= $cv['NgayBDThucTe'] ?>">
                    </div>
                    <div>
                        <label for="end-date-real">Ngày kết thúc thực tế</label>
                        <input type="datetime-local" id="end-date-real" name="end_date_real" value="<?= $cv['NgayKTThucTe'] ?>">
                    </div>
                </div>

                <label for="status">Trạng thái</label>
                <select id="status" name="status" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <?php
                    $statuses = ["Chưa bắt đầu", "Đang tiến hành", "Hoàn thành"];
                    foreach ($statuses as $st) {
                        $selected = ($cv['TrangThai'] == $st) ? 'selected' : '';
                        echo "<option value=\"$st\" $selected>$st</option>";
                    }
                    ?>
                </select>

                <button type="submit">LƯU THAY ĐỔI</button>
            </form>
        </section>
    </main>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "-- Tìm và chọn --",
                allowClear: true,
                width: '100%',
                language: {
                    noResults: () => "Không có kết quả phù hợp"
                },
                matcher: (params, data) => {
                    if ($.trim(params.term) === '') return data;
                    const term = params.term.toLowerCase();
                    const text = data.text.toLowerCase();
                    return text.includes(term) ? data : null;
                }
            });
        });

        function validateDateInputs(estStartId, estEndId, realStartId, realEndId) {
            const estStart = document.getElementById(estStartId);
            const estEnd = document.getElementById(estEndId);
            const realStart = document.getElementById(realStartId);
            const realEnd = document.getElementById(realEndId);

            [estStart, estEnd, realStart, realEnd].forEach(input => {
                input.addEventListener('change', () => {
                    if (estStart.value && estEnd.value && estEnd.value < estStart.value) {
                        alert("❌ Ngày kết thúc dự kiến phải sau ngày bắt đầu dự kiến!");
                        estEnd.value = '';
                    }
                    if (realStart.value && realEnd.value && realEnd.value < realStart.value) {
                        alert("❌ Ngày kết thúc thực tế phải sau ngày bắt đầu thực tế!");
                        realEnd.value = '';
                    }
                });
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            validateDateInputs('start-date-est', 'end-date-est', 'start-date-real', 'end-date-real');
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
