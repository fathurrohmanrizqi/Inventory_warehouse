<?php
$title = 'Hasil Pencarian';
require_once 'layout/header.php';
require_once 'config/database.php';

$q = $_GET['q'] ?? '';
$search = "%$q%";

// 1. Cari di Master Barang
$stmtBarang = $conn->prepare("SELECT b.*, k.nama_kategori 
                             FROM barang b 
                             LEFT JOIN kategori_barang k ON b.kategori_id = k.id 
                             WHERE b.kode_barang LIKE ? OR b.nama_barang LIKE ? OR b.description LIKE ?");
$stmtBarang->bind_param("sss", $search, $search, $search);
$stmtBarang->execute();
$resBarang = $stmtBarang->get_result();

// 2. Cari di Transaksi/Riwayat (Termasuk pencarian tanggal)
$stmtTrans = $conn->prepare("SELECT t.*, b.nama_barang, b.kode_barang, b.satuan, u.nama_lengkap 
                            FROM transaksi_stok t 
                            JOIN barang b ON t.barang_id = b.id 
                            JOIN users u ON t.user_id = u.id
                            WHERE t.tanggal LIKE ? OR t.keterangan LIKE ? OR b.nama_barang LIKE ?");
$stmtTrans->bind_param("sss", $search, $search, $search);
$stmtTrans->execute();
$resTrans = $stmtTrans->get_result();
?>

<div class="content-head">
    <div>
        <h2>Hasil Pencarian untuk: "<?= htmlspecialchars($q) ?>"</h2>
        <p class="subtitle">Menampilkan data barang dan transaksi yang relevan.</p>
    </div>
</div>

<?php if ($q == ''): ?>
    <div class="panel" style="padding: 40px; text-align: center;">
        <i class="bi bi-search" style="font-size: 48px; color: var(--text-muted); opacity: 0.3;"></i>
        <p style="margin-top: 16px; color: var(--text-muted);">Silakan masukkan kata kunci pencarian pada kolom di atas.</p>
    </div>
<?php else: ?>

    <!-- Bagian Hasil Barang -->
    <div class="panel" style="margin-bottom: 32px;">
        <div class="panel-head">
            <h3><i class="bi bi-box"></i> Hasil Data Barang (<?= $resBarang->num_rows ?>)</h3>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>SKU & Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Saat Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resBarang->num_rows > 0): ?>
                    <?php while($row = $resBarang->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="name"><?= htmlspecialchars($row['nama_barang']) ?></div>
                            <div class="mono" style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($row['kode_barang']) ?></div>
                        </td>
                        <td><span class="badge gray"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                        <td class="mono"><?= number_format($row['stock']) ?> <?= htmlspecialchars($row['satuan']) ?></td>
                        <td>
                            <div class="act">
                                <a href="<?= BASE_URL ?>modules/barang/edit.php?id=<?= $row['id'] ?>"><i class="bi bi-pencil-square"></i></a>
                                <a href="<?= BASE_URL ?>modules/persediaan/index.php"><i class="bi bi-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 24px; color: var(--text-muted);">Tidak ada barang yang cocok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bagian Hasil Transaksi -->
    <div class="panel">
        <div class="panel-head">
            <h3><i class="bi bi-clock-history"></i> Hasil Riwayat Transaksi (<?= $resTrans->num_rows ?>)</h3>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Tipe</th>
                    <th style="text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resTrans->num_rows > 0): ?>
                    <?php while($row = $resTrans->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600;"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                        </td>
                        <td>
                            <div class="name"><?= htmlspecialchars($row['nama_barang']) ?></div>
                            <div class="mono" style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($row['kode_barang']) ?></div>
                        </td>
                        <td>
                            <?php if($row['jenis_transaksi'] == 'masuk'): ?>
                                <span class="badge blue">Masuk</span>
                            <?php else: ?>
                                <span class="badge gray">Keluar</span>
                            <?php endif; ?>
                        </td>
                        <td class="mono" style="text-align: right; font-weight: 700; color: <?= $row['jenis_transaksi'] == 'masuk' ? 'var(--success)' : 'var(--danger)' ?>;">
                            <?= $row['jenis_transaksi'] == 'masuk' ? '+' : '-' ?><?= number_format($row['jumlah']) ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 24px; color: var(--text-muted);">Tidak ada transaksi yang cocok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<?php require_once 'layout/footer.php'; ?>
