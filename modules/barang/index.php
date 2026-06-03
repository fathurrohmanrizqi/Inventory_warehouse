<?php
$current_page = 'master';
$title = 'Master Data Barang';
require_once '../../layout/header.php';
require_once '../../config/database.php';

$query = "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori_barang k ON b.kategori_id = k.id ORDER BY b.id DESC";
$result = $conn->query($query);
?>

<div class="tabs">
    <a class="active" href="<?= BASE_URL ?>modules/barang/index.php">Daftar Barang</a>
    <a href="<?= BASE_URL ?>modules/kategori/index.php">Kategori</a>
    <a href="<?= BASE_URL ?>modules/user/index.php">Pengguna</a>
</div>

<div class="content-head">
    <div>
        <h2>Daftar Barang</h2>
        <p class="subtitle">Kelola informasi produk, SKU, dan kategori material.</p>
    </div>
    <a href="tambah.php" class="btn dark"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>SKU & Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Min. Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <div class="name"><?= htmlspecialchars($row['nama_barang']) ?></div>
                    <div class="mono" style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($row['kode_barang']) ?></div>
                </td>
                <td><span class="badge gray"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                <td class="mono"><?= number_format($row['stock']) ?> <small><?= htmlspecialchars($row['satuan']) ?></small></td>
                <td class="mono"><?= number_format($row['minimum_stock']) ?></td>
                <td>
                    <div class="act">
                        <a href="edit.php?id=<?= $row['id'] ?>" title="Edit"><i class="bi bi-pencil-square"></i></a> 
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="del" title="Hapus" onclick="return confirm('Yakin menghapus barang ini?')"><i class="bi bi-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if($result->num_rows == 0): ?>
            <tr><td colspan="5" style="text-align:center; padding: 40px; color: var(--text-muted);">Data barang belum tersedia.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>
