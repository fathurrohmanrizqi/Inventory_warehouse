<?php
session_start();
require_once '../../config/database.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'] ?? 0;
// Jangan hapus diri sendiri
if ($id == $_SESSION['user_id']) {
    echo "<script>alert('Tidak dapat menghapus akun sendiri!'); window.location.href='index.php';</script>";
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: index.php");
exit;
?>