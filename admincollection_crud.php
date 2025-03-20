<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $ten_bua = $_POST['ten_bua'];
        $mo_ta = $_POST['mo_ta'];
        $stmt = $conn->prepare("INSERT INTO bo_suu_tap (ten_bua, mo_ta) VALUES (?, ?)");
        $stmt->bind_param("ss", $ten_bua, $mo_ta);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $id_bua = $_POST['id_bua'];
        $conn->query("DELETE FROM bo_suu_tap WHERE id_bua=$id_bua");
    }
    header("Location: ../admin/manage_collections.html");
}
?>
