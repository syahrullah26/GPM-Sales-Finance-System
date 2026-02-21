<?php
include '../includes/koneksi.php';

if (isset($_GET['id']) && isset($_GET['plr_id'])) {
    $id = intval($_GET['id']);
    $plr_id = intval($_GET['plr_id']);
    $query = mysqli_query($konek, "DELETE FROM pengeluaran_jenis WHERE id = $id");

    if ($query) {
        header("Location: ../index.php?page=pengeluaran_edit&id=$plr_id");
        exit;
    } else {
        die("Gagal menghapus item dari database.");
    }
} else {
    die("Parameter tidak lengkap.");
}
