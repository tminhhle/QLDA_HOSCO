<?php
include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $sql = "SELECT congviec.*, nguoidung.HoTen, duan.TenDA 
            FROM congviec 
            JOIN nguoidung ON congviec.MaNguoiThucHien = nguoidung.MaNguoiDung
            JOIN duan ON congviec.MaDA = duan.MaDA 
            WHERE congviec.MaCV LIKE ? 
               OR congviec.TenCV LIKE ? 
               OR congviec.TrangThai LIKE ? 
               OR nguoidung.HoTen LIKE ? 
               OR duan.TenDA LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT congviec.*, nguoidung.HoTen, duan.TenDA 
            FROM congviec 
            JOIN nguoidung ON congviec.MaNguoiThucHien = nguoidung.MaNguoiDung
            JOIN duan ON congviec.MaDA = duan.MaDA";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Công Việc</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <link rel="stylesheet" href="../CSS/qlcv.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="#">Trang chủ</a>
            <a href="../php/qlda.php">Quản lý dự án</a>
            <a class="active" href="#">Quản lý công việc</a>
            <a href="#">Quản lý người dùng</a>
            <a href="#">Thống kê</a>
            <div class="user-info">
                <i class="fa-regular fa-circle-user"></i><span><strong>Minh</strong></span>
            </div>
        </nav>
    </header>

    <main>
        <div class="project-list">
            <h1>DANH SÁCH CÔNG VIỆC</h1>
            <form method="GET" class="actions">
                <a href="../php/tcv.php" class="btn-add"><strong>+ THÊM</strong></a>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="text" name="search" placeholder="Tìm kiếm theo mã, tên công việc, người thực hiện, dự án..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn-search">Tìm</button>
                    <?php if ($search !== ''): ?>
                        <a href="qlcv.php" class="btn-back">← Quay lại</a>
                    <?php endif; ?>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>MÃ CÔNG VIỆC</th>
                        <th>TÊN CÔNG VIỆC</th>
                        <th>NGƯỜI THỰC HIỆN</th>
                        <th>DỰ ÁN</th>
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
                            <td><?= $row['MaCV'] ?></td>
                            <td><?= $row['TenCV'] ?></td>
                            <td><?= $row['MaNguoiThucHien'] ?> - <?= $row['HoTen'] ?></td>
                            <td><?= $row['MaDA'] ?> - <?= $row['TenDA'] ?></td>
                            <td><?= $row['TrangThai'] ?></td>
                            <td><a href="xcv.php?MaCV=<?= $row['MaCV'] ?>"><i class="fa-regular fa-eye"></i></a></td>
                            <td><a href="scv.php?MaCV=<?= $row['MaCV'] ?>&keyword=<?= urlencode($search) ?>"><i class="fa-regular fa-pen-to-square"></i></a></td>
                            <td><i class="fa-regular delete-btn fa-trash-can" onclick="showPopup('<?= $row['MaCV'] ?>')"></i></td>
                        </tr>
                <?php }
                } else {
                    echo '<tr><td colspan="9" style="text-align:center;">Không tìm thấy công việc.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
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
    xhr.open("GET", "xoacv.php?MaCV=" + selectedProject, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = xhr.responseText.trim();
            if (response === "success") {
                alert("Đã xóa thành công công việc " + selectedProject);
                location.reload();
            } else if (response === "not_found") {
                alert("Công việc không tồn tại!");
            } else if (response === "not_allowed") {
                alert("Không thể xóa dự án đang tiến hành!");
            } else {
                alert("Xóa thất bại. Vui lòng thử lại.");
            }
        }
    };
    xhr.send();
    closePopup();
}

    </script>
</body>
</html>
