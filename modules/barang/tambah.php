<?php
$current_page = 'master';
$title = 'Tambah Barang';
require_once '../../layout/header.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $kategori_id = $_POST['kategori_id'];
    $satuan = $_POST['satuan'];
    $minimum_stock = $_POST['minimum_stock'] ?? 10;
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("INSERT INTO barang (kategori_id, kode_barang, nama_barang, satuan, minimum_stock, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $kategori_id, $kode_barang, $nama_barang, $satuan, $minimum_stock, $description);
    if ($stmt->execute()) {
        echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        $error = "Gagal menambah data: " . $conn->error;
    }
}
$kategori = $conn->query("SELECT * FROM kategori_barang");
?>

<div class="content-head">
    <div>
        <h2>Tambah Barang</h2>
        <p class="subtitle">Input data barang baru ke dalam sistem inventaris.</p>
    </div>
    <a href="index.php" class="btn"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="table-card" style="padding: 32px; max-width: 800px;">
    <?php if(isset($error)): ?>
    <div class="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="field">
                <label>Kode Barang (SKU)</label>
                <div class="input"><i class="bi bi-upc-scan"></i><input type="text" name="kode_barang" required placeholder="Contoh: BRG-001"></div>
            </div>
            <div class="field">
                <label>Nama Barang</label>
                <div class="input"><i class="bi bi-box"></i><input type="text" name="nama_barang" required placeholder="Nama lengkap barang"></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="field">
                <label>Kategori</label>
                <div class="input">
                    <i class="bi bi-tags"></i>
                    <select name="kategori_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php while($k = $kategori->fetch_assoc()): ?>
                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="field">
                <label>Satuan</label>
                <div class="input"><i class="bi bi-rulers"></i><input type="text" name="satuan" required placeholder="Contoh: Pcs, Box, Kg"></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="field">
                <label>Minimum Stok</label>
                <div class="input"><i class="bi bi-exclamation-octagon"></i><input type="number" name="minimum_stock" value="10" required></div>
            </div>
            <div class="field">
                <label>Deskripsi</label>
                <div class="input"><i class="bi bi-info-circle"></i><input type="text" name="description" placeholder="Keterangan singkat"></div>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn dark" style="padding: 0 40px;"><i class="bi bi-check-lg"></i> Simpan Barang</button>
        </div>
    </form>
</div>

<?php require_once '../../layout/footer.php'; ?>
