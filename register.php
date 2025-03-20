<?php
session_start();

// Kết nối database
$mysqli = new mysqli("localhost", "root", "", "bao_tang");

if ($mysqli->connect_errno) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form (fix tên input khớp)
    $username = $_POST['username'];
    $pass = $_POST['password']; // đổi thành 'password'
    $ho_ten = $_POST['ho_ten'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];
    $email = $_POST['email'];

    // Mã hóa mật khẩu
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Chuẩn bị câu lệnh SQL (sửa thêm dấu `user`)
    $stmt = $mysqli->prepare("INSERT INTO `user` (username, pass, ho_ten, sdt, dia_chi, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $hashed_password, $ho_ten, $sdt, $dia_chi, $email);

    if ($stmt->execute()) {
        echo "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
