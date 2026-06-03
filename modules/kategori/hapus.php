<?php
session_start();
require_once '../../config/database.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit; }

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("DELETE FROM kategori_barang WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: index.php");
exit;
?>