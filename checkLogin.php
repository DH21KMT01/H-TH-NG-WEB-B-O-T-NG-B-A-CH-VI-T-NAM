<?php
session_start();

// Kết nối database
$mysqli = new mysqli("localhost", "root", "", "bao_tang");

if ($mysqli->connect_errno) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Lấy thông tin user + quyền
    $stmt = $mysqli->prepare("
        SELECT user.id_user, user.pass, quyen_han.quyen 
        FROM user 
        JOIN quyen_han ON user.id_user = quyen_han.id_user 
        WHERE username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kiểm tra mật khẩu (hiện tại đang lưu thuần)
        if ($password === $row['pass']) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $username;
            $_SESSION['quyen'] = $row['quyen']; // Lưu quyền vào session luôn

            // Kiểm tra quyền
            if ($row['quyen'] === 'admin') {
                header("Location: admin_dashboard.html");
            } else {
                header("Location: user.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}

$mysqli->close();

?>
