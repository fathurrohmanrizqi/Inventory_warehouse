<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$current_page = $current_page ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?? 'KOKBagus Admin' ?></title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body data-page="<?= $current_page ?>">
    <div class="app">
        <aside class="sidebar">
            <a href="<?= BASE_URL ?>index.php" class="brand" style="text-decoration: none; display: block;">
                <h2>KOKBagus</h2>
                <small>Inventory System</small>
            </a>
            <nav class="menu">
                <a href="<?= BASE_URL ?>index.php" data-page="dashboard"><i class="bi bi-grid-fill ico"></i> Dashboard</a>
                <a href="<?= BASE_URL ?>modules/persediaan/index.php" data-page="persediaan"><i class="bi bi-box-seam ico"></i> Persediaan</a>
                <a href="<?= BASE_URL ?>modules/barang/index.php" data-page="master"><i class="bi bi-database ico"></i> Master Data</a>
                <a href="<?= BASE_URL ?>modules/laporan/index.php" data-page="laporan"><i class="bi bi-file-earmark-bar-graph ico"></i> Laporan</a>
            </nav>
            <div class="spacer"></div>
            <a class="logout" href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-right ico"></i> Logout</a>
            <div class="profile">
                <div class="avatar"><?= strtoupper(substr($_SESSION['nama_lengkap'], 0, 1)) ?></div>
                <div class="profile-info">
                    <span class="name"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></span>
                </div>
            </div>
        </aside>
        <main class="main">
            <header class="topbar">
                <div class="page-title">
                    <h1><?= $title ?? 'Toko Bangunan KOKBagus' ?></h1>
                </div>
                <form action="<?= BASE_URL ?>search.php" method="GET" class="search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Cari barang atau tanggal..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </form>
                <div class="top-icons">
                    <div class="icon-btn"><i class="bi bi-bell"></i></div>
                    <div class="settings-wrapper">
                        <div id="settings-btn" class="icon-btn" title="Pengaturan Tampilan">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div id="theme-menu" class="theme-dropdown">
                            <div class="theme-option" data-theme="light">
                                <i class="bi bi-sun"></i> Mode Terang
                            </div>
                            <div class="theme-option" data-theme="dark">
                                <i class="bi bi-moon-stars"></i> Mode Gelap
                            </div>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>modules/user/edit.php?id=<?= $_SESSION['user_id'] ?>" class="user-pill icon-btn" title="Edit Profil">
                        <i class="bi bi-person-circle"></i>
                    </a>
                </div>
            </header>
            <section class="content">
