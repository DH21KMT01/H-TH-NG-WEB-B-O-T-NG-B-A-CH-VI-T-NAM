<?php
include('config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
}

$id_user = $_SESSION['user_id'];
$ho_ten = $_POST['ho_ten'];
$sdt = $_POST['sdt'];
$dia_chi = $_POST['dia_chi'];
$email = $_POST['email'];

$stmt = $conn->prepare("UPDATE user SET ho_ten=?, sdt=?, dia_chi=?, email=? WHERE id_user=?");
$stmt->bind_param("ssssi", $ho_ten, $sdt, $dia_chi, $email, $id_user);
$stmt->execute();
header("Location: profile.html");
?>
