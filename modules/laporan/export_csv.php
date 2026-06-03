<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-t');

$query = "SELECT t.tanggal, b.kode_barang, b.nama_barang, t.jenis_transaksi, t.jumlah, b.satuan, u.nama_lengkap as nama_user 
          FROM transaksi_stok t 
          JOIN barang b ON t.barang_id = b.id 
          JOIN users u ON t.user_id = u.id 
          WHERE t.tanggal BETWEEN ? AND ? 
          ORDER BY t.tanggal DESC, t.id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $tgl_awal, $tgl_akhir);
$stmt->execute();
$result = $stmt->get_result();

// Set header to force download as CSV
$filename = "Laporan_Transaksi_" . $tgl_awal . "_ke_" . $tgl_akhir . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Open output stream
$output = fopen('php://output', 'w');

// Write column headers
fputcsv($output, array('Tanggal', 'SKU', 'Nama Barang', 'Tipe', 'Jumlah', 'Satuan', 'Petugas'));

// Write data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, array(
        $row['tanggal'],
        $row['kode_barang'],
        $row['nama_barang'],
        ucfirst($row['jenis_transaksi']),
        $row['jumlah'],
        $row['satuan'],
        $row['nama_user']
    ));
}

fclose($output);
exit;
?>
