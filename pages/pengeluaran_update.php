<?php
include '../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data utama
    $pengeluaran_id = $_POST['pengeluaran_id'];
    $no_pengeluaran = $_POST['no_pengeluaran'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $invoice_ids = $_POST['invoice_id'];
    $jenis_pengeluaran = $_POST['jenis_pengeluaran'];
    $nominal_jenis = $_POST['nominal_jenis'];

    // Validasi dasar
    if (empty($pengeluaran_id) || empty($no_pengeluaran) || empty($tanggal) || empty($invoice_ids) || empty($jenis_pengeluaran) || empty($nominal_jenis)) {
        die("Semua field harus diisi.");
    }

    // Hitung total pengeluaran
    $total_pengeluaran = 0;
    foreach ($nominal_jenis as $nominal) {
        $total_pengeluaran += floatval($nominal);
    }

    // Update tabel pengeluaran
    $stmt = $konek->prepare("UPDATE pengeluaran SET no_pengeluaran=?, tanggal=?, total_pengeluaran=?, keterangan=? WHERE id=?");
    $stmt->bind_param("ssdsi", $no_pengeluaran, $tanggal, $total_pengeluaran, $keterangan, $pengeluaran_id);
    $stmt->execute();
    $stmt->close();

    // Bersihkan data lama pada tabel pengeluaran_items & pengeluaran_jenis
    $konek->query("DELETE FROM pengeluaran_items WHERE pengeluaran_id = $pengeluaran_id");
    $konek->query("DELETE FROM pengeluaran_jenis WHERE pengeluaran_id = $pengeluaran_id");

    // Masukkan ulang jenis pengeluaran
    for ($i = 0; $i < count($jenis_pengeluaran); $i++) {
        $jenis = $jenis_pengeluaran[$i];
        $nominal = floatval($nominal_jenis[$i]);

        if (!empty($jenis) && $nominal > 0) {
            $stmt = $konek->prepare("INSERT INTO pengeluaran_jenis (pengeluaran_id, jenis_pengeluaran, nominal) VALUES (?, ?, ?)");
            $stmt->bind_param("isd", $pengeluaran_id, $jenis, $nominal);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Bagi nominal ke setiap invoice secara merata
    $jumlah_invoice = count($invoice_ids);
    $nominal_per_invoice = $jumlah_invoice > 0 ? $total_pengeluaran / $jumlah_invoice : 0;

    foreach ($invoice_ids as $invoice_id) {
        if (!empty($invoice_id)) {
            $stmt = $konek->prepare("INSERT INTO pengeluaran_items (pengeluaran_id, invoice_id, nominal) VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $pengeluaran_id, $invoice_id, $nominal_per_invoice);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirect setelah update
    echo "<script>alert('Data pengeluaran berhasil diubah!'); window.location.href='../index.php?page=pengeluaran';</script>";
    exit();
} else {
    echo "Invalid request.";
}
?>
