<?php
$current_page = 'master';
$title = 'Tambah Pengguna - KOKBagus';
require_once '../../layout/header.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $nama_lengkap = $_POST['nama_lengkap'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, nama_lengkap) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $nama_lengkap);
    if ($stmt->execute()) {
        echo "<script>alert('Pengguna berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        $error = "Gagal menambah data.";
    }
}
?>

<div class="content-head">
    <div>
        <h2>Tambah Pengguna</h2>
        <p class="subtitle">Daftarkan admin atau staf gudang baru.</p>
    </div>
    <a href="index.php" class="btn">Kembali</a>
</div>

<div class="table-card" style="padding: 24px;">
    <?php if(isset($error)): ?><div class="alert"><b>● &nbsp; Error</b><br><span><?= $error ?></span></div><?php endif; ?>
    
    <form method="POST" action="" class="form-new">
        <div class="field">
            <label>USERNAME</label>
            <div class="input"><span>♙</span><input type="text" name="username" required placeholder="username_staf"></div>
        </div>
        <div class="field">
            <label>PASSWORD</label>
            <div class="input"><span>🔒</span><input type="password" name="password" required placeholder="********"></div>
        </div>
        <div class="field">
            <label>NAMA LENGKAP</label>
            <div class="input"><span>👤</span><input type="text" name="nama_lengkap" required placeholder="Nama Lengkap"></div>
        </div>
        <div style="margin-top: 24px;">
            <button type="submit" class="btn dark" style="width: 100%; justify-content: center; height: 42px;">DAFTARKAN PENGGUNA</button>
        </div>
    </form>
</div>

<style>
.form-new { max-width: 600px; }
.form-new .field { margin-bottom: 18px; }
</style>

<?php require_once '../../layout/footer.php'; ?>
