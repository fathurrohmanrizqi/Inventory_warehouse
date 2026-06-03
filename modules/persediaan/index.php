<?php
$current_page = 'persediaan';
$title = 'Manajemen Persediaan';
require_once '../../layout/header.php';
require_once '../../config/database.php';

$query = "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori_barang k ON b.kategori_id = k.id ORDER BY b.stock ASC";
$result = $conn->query($query);
?>

<div class="content-head">
    <div>
        <h2>Persediaan Barang</h2>
        <p class="subtitle">Monitor stok material dan lakukan penyesuaian barang masuk/keluar.</p>
    </div>
    <div class="actions">
        <a href="keluar.php" class="btn"><i class="bi bi-box-arrow-up"></i> Barang Keluar</a>
        <a href="masuk.php" class="btn dark"><i class="bi bi-box-arrow-down"></i> Barang Masuk</a>
    </div>
</div>

<div class="table-card">
    <div class="filterbar">
        <i class="bi bi-filter"></i>
        <span>Filter:</span>
        <select class="date-input" style="width: 200px;">
            <option>Semua Kategori</option>
        </select>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th style="text-align: right;">Stok Saat Ini</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="mono"><?= htmlspecialchars($row['kode_barang']) ?></td>
                <td class="name"><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td class="mono" style="text-align: right; font-weight: 700; font-size: 15px;">
                    <?= number_format($row['stock']) ?> <span style="font-weight: 400; font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($row['satuan']) ?></span>
                </td>
                <td>
                    <?php if($row['stock'] <= $row['minimum_stock'] && $row['stock'] > 0): ?>
                        <span class="badge orange">Stok Rendah</span>
                    <?php elseif($row['stock'] > 0): ?>
                        <span class="badge green">Tersedia</span>
                    <?php else: ?>
                        <span class="badge red">Habis</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="act">
                        <a href="masuk.php?barang_id=<?= $row['id'] ?>" title="Tambah Stok"><i class="bi bi-plus-circle"></i></a>
                        <a href="keluar.php?barang_id=<?= $row['id'] ?>" title="Kurangi Stok"><i class="bi bi-dash-circle"></i></a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if($result->num_rows == 0): ?>
            <tr><td colspan="6" style="text-align:center; padding: 40px;">Belum ada data barang.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>
