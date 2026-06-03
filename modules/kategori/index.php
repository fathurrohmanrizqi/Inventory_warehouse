<?php
$current_page = 'master';
$title = 'Kategori Barang';
require_once '../../layout/header.php';
require_once '../../config/database.php';

// Stats
$resKat = $conn->query("SELECT COUNT(id) as total FROM kategori_barang");
$totalKategori = $resKat->fetch_assoc()['total'];

$resBarang = $conn->query("SELECT COUNT(id) as total FROM barang");
$totalBarang = $resBarang->fetch_assoc()['total'];

// Kategori with critical stock items
$queryKritis = "SELECT COUNT(DISTINCT kategori_id) as total FROM barang WHERE stock < minimum_stock";
$resKritis = $conn->query($queryKritis);
$kategoriKritis = $resKritis->fetch_assoc()['total'] ?? 0;

$result = $conn->query("SELECT * FROM kategori_barang ORDER BY id DESC");
?>

<div class="tabs">
    <a href="<?= BASE_URL ?>modules/barang/index.php">Daftar Barang</a>
    <a class="active" href="<?= BASE_URL ?>modules/kategori/index.php">Kategori</a>
    <a href="<?= BASE_URL ?>modules/user/index.php">Pengguna</a>
</div>

<div class="content-head">
    <div>
        <h2>Kategori Barang</h2>
        <p class="subtitle">Klasifikasi produk untuk pengorganisasian inventaris yang lebih baik.</p>
    </div>
    <a href="tambah.php" class="btn dark"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
</div>

<div class="stats3">
    <div class="stat">
        <i class="bi bi-tags"></i>
        <div>
            <span>Total Kategori</span>
            <b><?= $totalKategori ?></b>
        </div>
    </div>
    <div class="stat orange">
        <i class="bi bi-box"></i>
        <div>
            <span>Item Terdata</span>
            <b><?= $totalBarang ?></b>
        </div>
    </div>
    <div class="stat red">
        <i class="bi bi-exclamation-octagon"></i>
        <div>
            <span>Kategori Kritis</span>
            <b><?= $kategoriKritis ?></b>
        </div>
    </div>
</div>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px;">ID</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="mono">#<?= $row['id'] ?></td>
                <td class="name"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td style="color: var(--text-muted);"><?= htmlspecialchars($row['description'] ?? '-') ?></td>
                <td>
                    <div class="act">
                        <a href="edit.php?id=<?= $row['id'] ?>" title="Edit"><i class="bi bi-pencil-square"></i></a> 
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="del" title="Hapus" onclick="return confirm('Yakin ingin menghapus kategori ini?')"><i class="bi bi-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if($result->num_rows == 0): ?>
            <tr><td colspan="4" style="text-align:center; padding: 40px;">Data kategori kosong.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../layout/footer.php'; ?>
