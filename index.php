<?php
$current_page = 'dashboard';
$title = 'Dashboard';
require_once 'layout/header.php';
require_once 'config/database.php';

// Total Barang
$resBarang = $conn->query("SELECT COUNT(id) as total FROM barang");
$totalBarang = $resBarang->fetch_assoc()['total'];

// Total Stok Masuk (Bulan Ini)
$resMasuk = $conn->query("SELECT SUM(jumlah) as total FROM transaksi_stok WHERE jenis_transaksi='masuk' AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
$totalMasuk = $resMasuk->fetch_assoc()['total'] ?? 0;

// Total Stok Keluar (Bulan Ini)
$resKeluar = $conn->query("SELECT SUM(jumlah) as total FROM transaksi_stok WHERE jenis_transaksi='keluar' AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
$totalKeluar = $resKeluar->fetch_assoc()['total'] ?? 0;

// Hitung stok kritis berdasarkan minimum_stock masing-masing barang
$resKritis = $conn->query("SELECT COUNT(id) as total FROM barang WHERE stock < minimum_stock");
$stokKritisCount = $resKritis->fetch_assoc()['total'] ?? 0;

// Ambil 5 stok terbanyak
$stokTerbanyak = [];
$resTerbanyak = $conn->query("SELECT kode_barang, nama_barang, stock FROM barang ORDER BY stock DESC LIMIT 5");
while($row = $resTerbanyak->fetch_assoc()){
    $stokTerbanyak[] = $row;
}

// Data untuk Grafik (Transaksi Bulan Berjalan)
$dailyLabels = [];
$dailyMasuk = [];
$dailyKeluar = [];

$resDaily = $conn->query("SELECT DATE(tanggal) as tgl, 
    SUM(CASE WHEN jenis_transaksi = 'masuk' THEN jumlah ELSE 0 END) as masuk,
    SUM(CASE WHEN jenis_transaksi = 'keluar' THEN jumlah ELSE 0 END) as keluar
    FROM transaksi_stok 
    WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())
    GROUP BY DATE(tanggal)
    ORDER BY DATE(tanggal) ASC");

while($row = $resDaily->fetch_assoc()) {
    $dailyLabels[] = date('d M', strtotime($row['tgl']));
    $dailyMasuk[] = (int)$row['masuk'];
    $dailyKeluar[] = (int)$row['keluar'];
}
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-head">
    <div>
        <h2>Dashboard Overview</h2>
        <p class="subtitle">Pantau pergerakan stok dan inventaris toko Anda secara real-time.</p>
    </div>
    <div class="actions">
        <a href="<?= BASE_URL ?>modules/laporan/index.php" class="btn"><i class="bi bi-download"></i> Laporan</a>
        <a href="<?= BASE_URL ?>modules/persediaan/masuk.php" class="btn dark"><i class="bi bi-plus-lg"></i> Tambah Stok</a>
    </div>
</div>

<div class="cards">
    <div class="card">
        <div class="card-title">Total Barang</div>
        <div class="card-num"><?= number_format($totalBarang) ?></div>
        <small><i class="bi bi-info-circle"></i> Item terdaftar</small>
    </div>
    <div class="card">
        <div class="card-title">Stok Masuk</div>
        <div class="card-num"><?= number_format($totalMasuk) ?></div>
        <small><i class="bi bi-arrow-up-right text-success"></i> Bulan ini</small>
    </div>
    <div class="card">
        <div class="card-title">Stok Keluar</div>
        <div class="card-num"><?= number_format($totalKeluar) ?></div>
        <small><i class="bi bi-arrow-down-right text-danger"></i> Bulan ini</small>
    </div>
    <div class="card red">
        <div class="card-title">Stok Kritis</div>
        <div class="card-num"><?= $stokKritisCount ?></div>
        <small><i class="bi bi-exclamation-triangle"></i> Perlu restock</small>
    </div>
</div>

<div class="dash-grid">
    <div class="panel">
        <div class="panel-head" style="display: block; padding-bottom: 15px;">
            <h3 style="margin-bottom: 4px;">Grafik Transaksi Bulan Ini</h3>
            <p class="subtitle" style="font-size: 13px; font-weight: 400;">Perbandingan barang masuk dan barang keluar berdasarkan tanggal transaksi.</p>
        </div>
        <div style="padding: 24px;">
            <canvas id="inventoryChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head">
            <h3>Stok Terbanyak</h3>
            <i class="bi bi-three-dots-vertical"></i>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: right;">Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($stokTerbanyak as $st): ?>
                <tr>
                    <td class="name"><?= htmlspecialchars($st['nama_barang']) ?><br><small class="mono" style="font-weight: 400; color: var(--text-muted);"><?= $st['kode_barang'] ?></small></td>
                    <td style="text-align: right; font-weight: 700;"><?= number_format($st['stock']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="padding: 16px; text-align: center;">
            <a href="<?= BASE_URL ?>modules/persediaan/index.php" style="text-decoration:none; color: var(--primary); font-weight: 600; font-size: 13px;">Lihat Semua Inventaris <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('inventoryChart').getContext('2d');
const inventoryChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dailyLabels) ?>,
        datasets: [
            {
                label: 'Barang Masuk',
                data: <?= json_encode($dailyMasuk) ?>,
                borderColor: '#4f46e5',
                backgroundColor: '#4f46e5',
                tension: 0.3,
                fill: false,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#4f46e5'
            },
            {
                label: 'Barang Keluar',
                data: <?= json_encode($dailyKeluar) ?>,
                borderColor: '#ef4444',
                backgroundColor: '#ef4444',
                tension: 0.3,
                fill: false,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#ef4444'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                align: 'start',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: { family: 'Inter', size: 12, weight: '600' }
                }
            },
            tooltip: {
                backgroundColor: '#1e293b',
                padding: 12,
                titleFont: { size: 14, weight: '700' },
                bodyFont: { size: 13 },
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { family: 'Inter', size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: { font: { family: 'Inter', size: 11 } }
            }
        }
    }
});
</script>

<?php require_once 'layout/footer.php'; ?>
