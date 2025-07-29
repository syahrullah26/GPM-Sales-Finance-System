<?php
include '../includes/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$pengeluaran_id = intval($_GET['id']);

// Hapus dari pengeluaran_items
mysqli_query($konek, "DELETE FROM pengeluaran_items WHERE pengeluaran_id = $pengeluaran_id");

// Hapus dari pengeluaran_jenis
mysqli_query($konek, "DELETE FROM pengeluaran_jenis WHERE pengeluaran_id = $pengeluaran_id");

// Hapus dari pengeluaran utama
$hapus = mysqli_query($konek, "DELETE FROM pengeluaran WHERE id = $pengeluaran_id");

if ($hapus) {
    echo "<script>alert('Data pengeluaran berhasil dihapus.'); window.location.href = '../index.php?page=pengeluaran';</script>";
} else {
    echo "<script>alert('Gagal menghapus data pengeluaran.'); window.location.href = '../index.php?page=pengeluaran';</script>";
}
