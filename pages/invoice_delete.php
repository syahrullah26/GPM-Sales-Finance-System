<?php
include '../includes/koneksi.php';

if (isset($_GET['id'])) {
    $invoice_id = (int)$_GET['id'];

    // Hapus semua item yang terkait dengan invoice ini
    $hapus_items = mysqli_query($konek, "DELETE FROM invoice_items WHERE invoice_id = $invoice_id");

    // Hapus invoice utama
    $hapus_invoice = mysqli_query($konek, "DELETE FROM invoices WHERE id = $invoice_id");

    if ($hapus_invoice) {
        // Redirect dengan notifikasi sukses
        header("Location: ../index.php?page=rugiLaba&deleted=$invoice_id");
        exit;
    } else {
        echo "Gagal menghapus data invoice.";
    }
} else {
    echo "ID invoice tidak ditemukan!";
}
?>
