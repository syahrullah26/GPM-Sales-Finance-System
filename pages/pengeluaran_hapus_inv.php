<?php
include '../includes/koneksi.php';

if (isset($_GET['id']) && isset($_GET['inv_id'])) {
    $invoice_id = intval($_GET['id']);
    $pengeluaran_id = intval($_GET['inv_id']);

    $hapus = mysqli_query($konek, "DELETE FROM pengeluaran_items WHERE invoice_id = $invoice_id AND pengeluaran_id = $pengeluaran_id");

    if ($hapus) {

        $result = mysqli_query($konek, "SELECT SUM(nominal) AS total FROM pengeluaran_jenis WHERE pengeluaran_id = $pengeluaran_id");
        $row = mysqli_fetch_assoc($result);
        $total_pengeluaran = $row['total'];


        $res_invs = mysqli_query($konek, "SELECT COUNT(*) AS total FROM pengeluaran_items WHERE pengeluaran_id = $pengeluaran_id");
        $row_invs = mysqli_fetch_assoc($res_invs);
        $jumlah_invoice = $row_invs['total'];

        if ($jumlah_invoice > 0) {
            $nominal_per_invoice = $total_pengeluaran / $jumlah_invoice;

            mysqli_query($konek, "
                UPDATE pengeluaran_items
                SET nominal = $nominal_per_invoice
                WHERE pengeluaran_id = $pengeluaran_id
            ");
        }

        header("Location: ../index.php?page=pengeluaran_edit&id=$pengeluaran_id");
        exit;
    } else {
        die("Gagal menghapus item dari database.");
    }
} else {
    die("Parameter tidak lengkap.");
}
