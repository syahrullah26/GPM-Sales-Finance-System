<?php
include '../includes/koneksi.php'; 

if (!isset($_GET['id'])) {
    echo "ID invoice tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$cek = mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    echo "Invoice tidak ditemukan.";
    exit;
}

if ($data['status'] == 'sudah bayar') {
    echo "Invoice ini sudah dibayar.";
    exit;
}

$update = mysqli_query($konek, "UPDATE invoices SET status = 'sudah bayar', tanggal_bayar = NOW() WHERE id = $id");

if ($update) {
    echo "<script>alert('Invoice telah dibayar!'); window.location.href='../index.php?page=rugiLaba';</script>";
    exit;
} else {
    echo "Gagal mengubah status invoice.";
}
?>
