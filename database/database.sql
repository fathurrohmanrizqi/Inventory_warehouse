CREATE DATABASE IF NOT EXISTS db_inventory_jewepe;
USE db_inventory_jewepe;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator');

CREATE TABLE `kategori_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori_barang` (`id`, `nama_kategori`) VALUES
(1, 'Semen'), (2, 'Cat'), (3, 'Paku');

CREATE TABLE `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`kategori_id`) REFERENCES `kategori_barang` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `barang` (`id`, `kategori_id`, `kode_barang`, `nama_barang`, `satuan`) VALUES
(1, 1, 'BRG001', 'Semen Tiga Roda 50kg', 'Sak'),
(2, 2, 'BRG002', 'Cat Dulux Putih 5kg', 'Galon'),
(3, 3, 'BRG003', 'Paku Payung 5cm', 'Kg');

CREATE TABLE `transaksi_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `jenis_transaksi` enum('masuk','keluar') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `transaksi_stok` (`id`, `barang_id`, `jenis_transaksi`, `jumlah`, `tanggal`, `keterangan`, `user_id`) VALUES
(1, 1, 'masuk', 100, CURDATE(), 'Stok awal', 1),
(2, 2, 'masuk', 50, CURDATE(), 'Stok awal', 1),
(3, 1, 'keluar', 10, CURDATE(), 'Terjual', 1);
