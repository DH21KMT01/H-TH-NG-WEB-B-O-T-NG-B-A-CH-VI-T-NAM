<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bao_tang"; // Đổi tên DB đúng với thực tế

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();
?>
