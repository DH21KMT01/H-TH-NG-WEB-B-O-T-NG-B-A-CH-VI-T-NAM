<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "bao_tang");

if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Kết nối DB thất bại"]);
    exit;
}

$sql = "SELECT user.id_user, username, ho_ten, sdt, dia_chi, email, quyen 
        FROM user 
        LEFT JOIN quyen_han ON user.id_user = quyen_han.id_user";

$result = $mysqli->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>
