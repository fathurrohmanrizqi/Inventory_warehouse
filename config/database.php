<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_inventory_jewepe";

define('BASE_URL', 'http://localhost/lspgudang/');

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>