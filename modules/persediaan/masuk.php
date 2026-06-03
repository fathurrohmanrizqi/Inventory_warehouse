<?php
$current_page = 'persediaan';
$title = 'Barang Masuk';
require_once '../../layout/header.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $user_id = $_SESSION['user_id'];

    if ($jumlah > 0) {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO transaksi_stok (barang_id, jenis_transaksi, jumlah, tanggal, keterangan, user_id) VALUES (?, 'masuk', ?, ?, ?, ?)");
            $stmt->bind_param("iissi", $barang_id, $jumlah, $tanggal, $keterangan, $user_id);
            $stmt->execute();

            $stmtUpdate = $conn->prepare("UPDATE barang SET stock = stock + ? WHERE id = ?");
            $stmtUpdate->bind_param("ii", $jumlah, $barang_id);
            $stmtUpdate->execute();

            $conn->commit();
            echo "<script>alert('Barang masuk berhasil dicatat!'); window.location.href='index.php';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Gagal mencatat transaksi: " . $e->getMessage();
        }
    } else {
        $error = "Jumlah harus lebih dari 0.";
    }
}

$barang_id_select = $_GET['barang_id'] ?? '';
$barang = $conn->query("SELECT id, kode_barang, nama_barang FROM barang ORDER BY nama_barang ASC");
?>

<div class="content-head">
    <div>
        <h2>Catat Barang Masuk</h2>
        <p class="subtitle">Tambahkan stok barang yang baru datang ke gudang.</p>
    </div>
    <a href="index.php" class="btn"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="table-card" style="padding: 32px; max-width: 600px;">
    <?php if (isset($error)): ?>
        <div class="alert"><i class="bi bi-exclamation-circle-fill"></i> <?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="field">
            <label>Pilih Barang</label>
            <div class="input">
                <i class="bi bi-box-seam"></i>
                <select name="barang_id" required>
                    <option value="">-- Cari Barang --</option>
                    <?php while($b = $barang->fetch_assoc()): ?>
                        <option value="<?= $b['id'] ?>" <?= $b['id'] == $barang_id_select ? 'selected' : '' ?>><?= $b['kode_barang'] ?> - <?= htmlspecialchars($b['nama_barang']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="field">
            <label>Jumlah Masuk</label>
            <div class="input"><i class="bi bi-plus-circle"></i><input type="number" name="jumlah" min="1" required placeholder="0"></div>
        </div>
        <div class="field">
            <label>Tanggal Transaksi</label>
            <div class="input"><i class="bi bi-calendar-event"></i><input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required></div>
        </div>
        <div class="field">
            <label>Keterangan</label>
            <div class="input"><i class="bi bi-chat-left-text"></i><input type="text" name="keterangan" placeholder="Contoh: Pengadaan rutin vendor X"></div>
        </div>
        <div style="margin-top: 32px;">
            <button type="submit" class="btn dark" style="width: 100%; height: 44px;"><i class="bi bi-check2-circle"></i> Simpan Transaksi Masuk</button>
        </div>
    </form>
</div>

<?php require_once '../../layout/footer.php'; ?>
