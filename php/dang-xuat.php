<?php
session_start();
session_destroy();
header("Location: dang-nhap.php"); // hoặc trang bạn muốn chuyển về sau khi đăng xuất
exit;
?>
