<?php
session_start();

// Hủy toàn bộ session
session_unset();
session_destroy();

// Quay về trang chủ hoặc trang login
header("Location: index.html");
exit;
?>
