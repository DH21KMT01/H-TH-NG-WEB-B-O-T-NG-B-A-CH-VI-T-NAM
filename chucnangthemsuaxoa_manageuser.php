<?php
$mysqli = new mysqli("localhost", "root", "", "bao_tang");

// Kiểm tra lỗi kết nối
if ($mysqli->connect_errno) {
    die("Lỗi kết nối MySQL: " . $mysqli->connect_error);
}

// Xử lý POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // --- Thêm User ---
    if ($action === 'add') {
        $username = $_POST['username'];
        $pass_raw = $_POST['pass'];
        $pass = password_hash($pass_raw, PASSWORD_DEFAULT); // mã hóa
        $ho_ten = $_POST['ho_ten'];
        $email = $_POST['email'];
        $sdt = $_POST['sdt'];
        $dia_chi = $_POST['dia_chi'];
        $quyen = $_POST['quyen'];

        // Kiểm tra username hoặc email đã tồn tại
        $check = $mysqli->prepare("SELECT id_user FROM user WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Trùng username hoặc email
            echo "<script>alert('Username hoặc Email đã tồn tại!'); window.location.href='manage_users.html';</script>";
            exit;
        }
        $check->close();

        // Thêm user
        $stmt = $mysqli->prepare("INSERT INTO user (username, pass, ho_ten, sdt, dia_chi, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $pass, $ho_ten, $sdt, $dia_chi, $email);
        $stmt->execute();
        $last_id = $stmt->insert_id;
        $stmt->close();

        // Thêm quyền
        $stmt2 = $mysqli->prepare("INSERT INTO quyen_han (id_user, quyen) VALUES (?, ?)");
        $stmt2->bind_param("is", $last_id, $quyen);
        $stmt2->execute();
        $stmt2->close();

        header("Location: manage_users.html");
        exit;
    }

    // --- Sửa User ---
    if ($action === 'edit') {
        $id_user = $_POST['id_user'];
        $ho_ten = $_POST['ho_ten'];
        $email = $_POST['email'];
        $sdt = $_POST['sdt'];
        $dia_chi = $_POST['dia_chi'];
        $quyen = $_POST['quyen'];

        // Update user
        $stmt = $mysqli->prepare("UPDATE user SET ho_ten=?, sdt=?, dia_chi=?, email=? WHERE id_user=?");
        $stmt->bind_param("ssssi", $ho_ten, $sdt, $dia_chi, $email, $id_user);
        $stmt->execute();
        $stmt->close();

        // Update quyền
        $stmt2 = $mysqli->prepare("UPDATE quyen_han SET quyen=? WHERE id_user=?");
        $stmt2->bind_param("si", $quyen, $id_user);
        $stmt2->execute();
        $stmt2->close();

        header("Location: manage_users.html");
        exit;
    }

    // --- Xóa User ---
    if ($action === 'delete') {
        $id_user = $_POST['id_user'];

        // Delete user, quyen_han tự xóa vì ON DELETE CASCADE
        $stmt = $mysqli->prepare("DELETE FROM user WHERE id_user=?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $stmt->close();

        header("Location: manage_users.html");
        exit;
    }
}
?>
