<?php
$current_page = 'master';
$title = 'Edit Profil Pengguna';
require_once '../../layout/header.php';
require_once '../../config/database.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $password = $_POST['password'];

    // Cek username (kecuali username sendiri)
    $cek = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $cek->bind_param("si", $username, $id);
    $cek->execute();
    if($cek->get_result()->num_rows > 0){
        $error = "Username sudah digunakan oleh akun lain!";
    } else {
        if (!empty($password)) {
            $password_hash = md5($password);
            $stmt = $conn->prepare("UPDATE users SET username=?, nama_lengkap=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $nama_lengkap, $password_hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, nama_lengkap=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $nama_lengkap, $id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='index.php';</script>";
        } else {
            $error = "Gagal memperbarui data: " . $conn->error;
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if(!$data) { 
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>"; 
    exit; 
}
?>

<div class="content-head">
    <div>
        <h2>Edit Profil</h2>
        <p class="subtitle">Perbarui informasi akun dan kredensial akses Anda.</p>
    </div>
    <a href="index.php" class="btn"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="table-card" style="padding: 32px; max-width: 600px;">
    <?php if(isset($error)): ?>
        <div class="alert"><i class="bi bi-exclamation-circle-fill"></i> <?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="field">
            <label>Username</label>
            <div class="input">
                <i class="bi bi-person"></i>
                <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required placeholder="Username">
            </div>
        </div>
        
        <div class="field">
            <label>Nama Lengkap</label>
            <div class="input">
                <i class="bi bi-card-text"></i>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required placeholder="Nama Lengkap">
            </div>
        </div>

        <div class="field">
            <label>Password Baru</label>
            <div class="input">
                <i class="bi bi-shield-lock"></i>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah">
            </div>
            <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">Biarkan kosong jika tetap ingin menggunakan password lama.</small>
        </div>

        <div style="margin-top: 32px;">
            <button type="submit" class="btn dark" style="width: 100%; height: 44px;"><i class="bi bi-save"></i> Simpan Perubahan Profil</button>
        </div>
    </form>
</div>

<?php require_once '../../layout/footer.php'; ?>
