<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-t');

$query = "SELECT t.*, b.kode_barang, b.nama_barang, b.satuan, u.nama_lengkap as nama_user 
          FROM transaksi_stok t 
          JOIN barang b ON t.barang_id = b.id 
          JOIN users u ON t.user_id = u.id 
          WHERE t.tanggal BETWEEN ? AND ? 
          ORDER BY t.tanggal DESC, t.id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $tgl_awal, $tgl_akhir);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Inventaris_<?= $tgl_awal ?>_ke_<?= $tgl_akhir ?></title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #1e293b; margin: 0; padding: 40px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #4f46e5; font-size: 24px; }
        .header p { margin: 5px 0; color: #64748b; font-size: 14px; }
        
        .info { margin-bottom: 20px; display: flex; justify-content: space-between; font-size: 13px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8fafc; text-align: left; padding: 12px 10px; border-bottom: 2px solid #e2e8f0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-masuk { background: #eef2ff; color: #4f46e5; }
        .badge-keluar { background: #f1f5f9; color: #64748b; }
        
        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        .signature { margin-top: 60px; border-top: 1px solid #1e293b; display: inline-block; width: 200px; text-align: center; padding-top: 5px; }

        @media print {
            @page { margin: 1cm; }
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>TOKO BANGUNAN KOKBagus</h1>
        <p>Sistem Manajemen Inventaris & Pergudangan Terpadu</p>
        <p>Jl. Raya Terbangun No.67 , Kota Whenyh</p>
    </div>

    <div class="info">
        <div>
            <strong>Laporan:</strong> Histori Transaksi Barang<br>
            <strong>Periode:</strong> <?= date('d M Y', strtotime($tgl_awal)) ?> - <?= date('d M Y', strtotime($tgl_akhir)) ?>
        </div>
        <div style="text-align: right;">
            <strong>Dicetak Pada:</strong> <?= date('d M Y, H:i') ?> WIB<br>
            <strong>Oleh:</strong> <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th style="text-align: right;">Jumlah</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <td style="font-family: monospace;"><?= htmlspecialchars($row['kode_barang']) ?></td>
                <td><strong><?= htmlspecialchars($row['nama_barang']) ?></strong></td>
                <td>
                    <span class="badge <?= $row['jenis_transaksi'] == 'masuk' ? 'badge-masuk' : 'badge-keluar' ?>">
                        <?= $row['jenis_transaksi'] == 'masuk' ? 'Masuk' : 'Keluar' ?>
                    </span>
                </td>
                <td style="text-align: right; font-weight: bold;">
                    <?= ($row['jenis_transaksi'] == 'masuk' ? '+' : '-') ?><?= number_format($row['jumlah']) ?>
                </td>
                <td><?= htmlspecialchars($row['satuan']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem KOKBagus Admin</p>
        <div class="signature">
            Penanggung Jawab Gudang
        </div>
    </div>
</body>
</html>
