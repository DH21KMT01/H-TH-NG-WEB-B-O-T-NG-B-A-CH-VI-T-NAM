<?php
include('config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
}

$id_user = $_SESSION['user_id'];
$id_tour = $_POST['id_tour'];
$so_luong = $_POST['so_luong'];

$stmt = $conn->prepare("INSERT INTO dat_ve (id_user, id_tour, so_luong) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $id_user, $id_tour, $so_luong);
$stmt->execute();
header("Location: profile.html");
?>
