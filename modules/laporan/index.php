<?php
$current_page = 'laporan';
$title = 'Laporan Transaksi';
require_once '../../layout/header.php';
require_once '../../config/database.php';

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

<div class="content-head">
    <div>
        <h2>Laporan Histori Transaksi</h2>
        <p class="subtitle">Analisis pergerakan masuk dan keluar barang dalam periode tertentu.</p>
    </div>
    <div class="actions">
        <a href="export_csv.php?tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>" class="btn"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
        <a href="cetak.php?tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>" target="_blank" class="btn dark"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
    </div>
</div>

<div class="table-card">
    <div class="filterbar">
        <form method="GET" action="" style="display:flex; align-items:center; gap:16px; width:100%">
            <div style="display:flex; align-items:center; gap:8px">
                <span style="font-size:12px; font-weight:700; color:var(--text-muted)">DARI</span>
                <input type="date" name="tgl_awal" class="date-input" value="<?= htmlspecialchars($tgl_awal) ?>">
            </div>
            <div style="display:flex; align-items:center; gap:8px">
                <span style="font-size:12px; font-weight:700; color:var(--text-muted)">SAMPAI</span>
                <input type="date" name="tgl_akhir" class="date-input" value="<?= htmlspecialchars($tgl_akhir) ?>">
            </div>
            <div style="flex:1"></div>
            <button type="submit" class="btn"><i class="bi bi-filter"></i> Terapkan Filter</button>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal & Waktu</th>
                <th>Item</th>
                <th>Jenis</th>
                <th style="text-align: right;">Jumlah</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <div style="font-weight:600"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                    <div style="font-size:11px; color:var(--text-muted)"><?= date('H:i', strtotime($row['created_at'] ?? '00:00')) ?> WIB</div>
                </td>
                <td class="name">
                    <?= htmlspecialchars($row['nama_barang']) ?>
                    <div class="mono" style="font-size:11px; font-weight:400; color:var(--text-muted)"><?= htmlspecialchars($row['kode_barang']) ?></div>
                </td>
                <td>
                    <?php if($row['jenis_transaksi'] == 'masuk'): ?>
                        <span class="badge blue">Barang Masuk</span>
                    <?php else: ?>
                        <span class="badge gray">Barang Keluar</span>
                    <?php endif; ?>
                </td>
                <td class="mono" style="text-align: right; font-weight: 700;">
                    <span style="color: <?= ($row['jenis_transaksi'] == 'masuk' ? 'var(--success)' : 'var(--danger)') ?>">
                        <?= ($row['jenis_transaksi'] == 'masuk' ? '+' : '-') ?><?= number_format($row['jumlah']) ?>
                    </span>
                    <small style="font-weight:400; color:var(--text-muted)"><?= htmlspecialchars($row['satuan']) ?></small>
                </td>
                <td>
                    <div style="display:flex; align-items:center; gap:8px">
                        <div class="avatar" style="width:24px; height:24px; font-size:10px"><?= strtoupper(substr($row['nama_user'], 0, 1)) ?></div>
                        <?= htmlspecialchars($row['nama_user']) ?>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if($result->num_rows == 0): ?>
            <tr><td colspan="5" style="text-align:center; padding: 40px; color: var(--text-muted);">Tidak ada riwayat transaksi pada periode ini.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>
