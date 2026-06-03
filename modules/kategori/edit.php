<?php
$current_page = 'master';
$title = 'Edit Kategori - KOKBagus';
require_once '../../layout/header.php';
require_once '../../config/database.php';

$id = $_GET['id'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = trim($_POST['nama_kategori']);
    $description = trim($_POST['description']);
    
    $stmt = $conn->prepare("UPDATE kategori_barang SET nama_kategori=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $nama_kategori, $description, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil diupdate!'); window.location.href='index.php';</script>";
    } else {
        $error = "Gagal update data: " . $conn->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM kategori_barang WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}
?>

<div class="content-head">
    <div>
        <h2>Edit Kategori</h2>
        <p class="subtitle">Perbarui informasi klasifikasi barang.</p>
    </div>
    <a href="index.php" class="btn">Kembali</a>
</div>

<div class="table-card" style="padding: 24px;">
    <?php if(isset($error)): ?><div class="alert"><b>● &nbsp; Error</b><br><span><?= $error ?></span></div><?php endif; ?>
    
    <form method="POST" action="" class="form-new">
        <div class="field">
            <label>NAMA KATEGORI</label>
            <div class="input"><span>▵</span><input type="text" name="nama_kategori" value="<?= htmlspecialchars($data['nama_kategori']) ?>" required></div>
        </div>
        <div class="field">
            <label>DESKRIPSI</label>
            <div class="input"><span>✎</span><input type="text" name="description" value="<?= htmlspecialchars($data['description'] ?? '') ?>" placeholder="Deskripsi kategori (opsional)"></div>
        </div>
        <div style="margin-top: 24px;">
            <button type="submit" class="btn dark" style="width: 100%; justify-content: center; height: 42px;">UPDATE KATEGORI</button>
        </div>
    </form>
</div>

<style>
.form-new { max-width: 600px; }
.form-new .field { margin-bottom: 18px; }
</style>

<?php require_once '../../layout/footer.php'; ?>
