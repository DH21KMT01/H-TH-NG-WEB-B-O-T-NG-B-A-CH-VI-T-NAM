<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $stmt = $conn->prepare("INSERT INTO post (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $id_post = $_POST['id_post'];
        $conn->query("DELETE FROM post WHERE id_post=$id_post");
    }
    header("Location: ../admin/manage_posts.html");
}
?>
