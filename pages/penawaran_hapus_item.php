<?php
include '../includes/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan";
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($konek, "DELETE FROM penawaran_items WHERE id = $id");

if ($query) {
    echo "OK";
} else {
    echo "Gagal: " . mysqli_error($konek);
}
