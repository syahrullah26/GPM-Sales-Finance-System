<?php
include '../includes/koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID penawaran tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$id = intval($_GET['id']);

// Cek apakah penawaran masih dalam status menunggu
$cek = mysqli_query($konek, "SELECT status FROM penawaran WHERE id = $id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    echo "<script>alert('Data penawaran tidak ditemukan.'); window.history.back();</script>";
    exit;
}

if ($data['status'] != 'menunggu') {
    echo "<script>alert('Hanya penawaran dengan status MENUNGGU yang bisa dibatalkan.'); window.history.back();</script>";
    exit;
}

// Update status menjadi 'batal'
$query = mysqli_query($konek, "UPDATE penawaran SET status = 'batal' WHERE id = $id");

if ($query) {
    echo "<script>alert('Penawaran berhasil ditolak (status: batal).'); window.location.href='../index.php?page=penawaran';</script>";
} else {
    echo "<script>alert('Gagal mengubah status penawaran.'); window.history.back();</script>";
}
?>
