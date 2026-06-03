<?php
$current_page = 'master';
$title = 'Edit Barang - KOKBagus';
require_once '../../layout/header.php';
require_once '../../config/database.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $kategori_id = $_POST['kategori_id'];
    $satuan = $_POST['satuan'];
    $minimum_stock = $_POST['minimum_stock'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE barang SET kategori_id=?, kode_barang=?, nama_barang=?, satuan=?, minimum_stock=?, description=? WHERE id=?");
    $stmt->bind_param("isssisi", $kategori_id, $kode_barang, $nama_barang, $satuan, $minimum_stock, $description, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Barang berhasil diupdate!'); window.location.href='index.php';</script>";
    } else {
        $error = "Gagal update data: " . $conn->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM barang WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if(!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>"; exit;
}
$kategori = $conn->query("SELECT * FROM kategori_barang");
?>

<div class="content-head">
    <div>
        <h2>Edit Barang</h2>
        <p class="subtitle">Perbarui informasi barang.</p>
    </div>
    <a href="index.php" class="btn">Kembali</a>
</div>

<div class="table-card" style="padding: 24px;">
    <?php if(isset($error)): ?><div class="alert"><b>● &nbsp; Error</b><br><span><?= $error ?></span></div><?php endif; ?>
    
    <form method="POST" action="" class="form-new">
        <div class="field">
            <label>KODE BARANG</label>
            <div class="input"><span>▰</span><input type="text" name="kode_barang" value="<?= htmlspecialchars($data['kode_barang']) ?>" required></div>
        </div>
        <div class="field">
            <label>NAMA BARANG</label>
            <div class="input"><span>📦</span><input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" required></div>
        </div>
        <div class="field">
            <label>KATEGORI</label>
            <div class="input">
                <span>≡</span>
                <select name="kategori_id" required style="border:0; background:transparent; outline:0; width:100%; font-size:13px; height: 100%;">
                    <option value="">Pilih Kategori</option>
                    <?php while($k = $kategori->fetch_assoc()): ?>
                    <option value="<?= $k['id'] ?>" <?= $k['id'] == $data['kategori_id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="field">
            <label>SATUAN</label>
            <div class="input"><span>⚖</span><input type="text" name="satuan" value="<?= htmlspecialchars($data['satuan']) ?>" required></div>
        </div>
        <div class="field">
            <label>MINIMUM STOK</label>
            <div class="input"><span>⚠</span><input type="number" name="minimum_stock" value="<?= htmlspecialchars($data['minimum_stock']) ?>" required></div>
        </div>
        <div class="field">
            <label>DESKRIPSI</label>
            <div class="input"><span>✎</span><input type="text" name="description" value="<?= htmlspecialchars($data['description'] ?? '') ?>"></div>
        </div>
        <div style="margin-top: 24px;">
            <button type="submit" class="btn dark" style="width: 100%; justify-content: center; height: 42px;">UPDATE DATA BARANG</button>
        </div>
    </form>
</div>

<style>
.form-new { max-width: 600px; }
.form-new .field { margin-bottom: 18px; }
</style>

<?php require_once '../../layout/footer.php'; ?>
