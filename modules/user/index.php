<?php
$current_page = 'master';
$title = 'Manajemen Pengguna';
require_once '../../layout/header.php';
require_once '../../config/database.php';

$resUserCount = $conn->query("SELECT COUNT(id) as total FROM users");
$totalUsers = $resUserCount->fetch_assoc()['total'];

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="tabs">
    <a href="<?= BASE_URL ?>modules/barang/index.php">Daftar Barang</a>
    <a href="<?= BASE_URL ?>modules/kategori/index.php">Kategori</a>
    <a class="active" href="<?= BASE_URL ?>modules/user/index.php">Pengguna</a>
</div>

<div class="content-head">
    <div>
        <h2>Manajemen Pengguna</h2>
        <p class="subtitle">Kelola hak akses admin dan staf operasional gudang.</p>
    </div>
    <a href="tambah.php" class="btn dark"><i class="bi bi-person-plus"></i> Tambah Pengguna</a>
</div>

<div class="dash-grid" style="grid-template-columns: 280px 1fr;">
    <aside>
        <div class="stat" style="margin-bottom: 24px;">
            <i class="bi bi-people"></i>
            <div>
                <span>Total Akun</span>
                <b><?= $totalUsers ?> Aktif</b>
            </div>
        </div>
        
        <div class="panel">
            <div class="panel-head"><h3>Filter Role</h3></div>
            <div style="padding: 16px;">
                <div style="display:flex; align-items:center; gap:12px; padding:10px; background:var(--primary-light); color:var(--primary); border-radius:8px; font-weight:600; cursor:pointer;">
                    <i class="bi bi-check-circle-fill"></i> Semua Pengguna
                </div>
            </div>
        </div>
    </aside>
    
    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="name">
                        <div style="display:flex; align-items:center; gap:12px">
                            <div class="avatar" style="width:32px; height:32px; background:#f1f5f9; color:var(--text-muted); font-size:12px">
                                <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                            </div>
                            <?= htmlspecialchars($row['nama_lengkap']) ?>
                        </div>
                    </td>
                    <td class="mono"><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <div class="act">
                            <a href="edit.php?id=<?= $row['id'] ?>" title="Edit"><i class="bi bi-pencil-square"></i></a> 
                            <?php if($row['id'] != $_SESSION['user_id']): ?>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="del" title="Hapus" onclick="return confirm('Yakin menghapus pengguna ini?')"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../layout/footer.php'; ?>
