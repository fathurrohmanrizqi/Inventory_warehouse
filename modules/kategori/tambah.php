<?php
$current_page = 'master';
$title = 'Tambah Kategori';
require_once '../../layout/header.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = trim($_POST['nama_kategori']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("INSERT INTO kategori_barang (nama_kategori, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama_kategori, $description);
    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        $error = "Gagal menambah data: " . $conn->error;
    }
}
?>

<div class="content-head">
    <div>
        <h2>Tambah Kategori Baru</h2>
        <p class="subtitle">Buat kelompok klasifikasi baru untuk pengelompokan barang.</p>
    </div>
    <a href="index.php" class="btn"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="table-card" style="padding: 32px; max-width: 600px;">
    <?php if(isset($error)): ?>
        <div class="alert"><i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="field">
            <label>Nama Kategori</label>
            <div class="input"><i class="bi bi-tag"></i><input type="text" name="nama_kategori" required placeholder="Contoh: Material Dasar, Alat Pertukangan"></div>
        </div>
        <div class="field">
            <label>Deskripsi Kategori</label>
            <div class="input"><i class="bi bi-info-circle"></i><input type="text" name="description" placeholder="Keterangan singkat mengenai kategori ini"></div>
        </div>
        <div style="margin-top: 32px;">
            <button type="submit" class="btn dark" style="width: 100%; height: 44px;"><i class="bi bi-check-lg"></i> Simpan Kategori</button>
        </div>
    </form>
</div>

<?php require_once '../../layout/footer.php'; ?>
