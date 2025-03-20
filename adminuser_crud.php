<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'delete') {
        $id_user = $_POST['id_user'];
        $conn->query("DELETE FROM user WHERE id_user=$id_user");
    } elseif ($action === 'update_role') {
        $id_user = $_POST['id_user'];
        $quyen = $_POST['quyen'];
        $stmt = $conn->prepare("UPDATE quyen_han SET quyen=? WHERE id_user=?");
        $stmt->bind_param("si", $quyen, $id_user);
        $stmt->execute();
    }
    header("Location: ../admin/manage_users.html");
}
?>
